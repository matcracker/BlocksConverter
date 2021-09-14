<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter\world;

use Exception;
use FilesystemIterator;
use InvalidStateException;
use matcracker\BlocksConverter\BlocksMap;
use matcracker\BlocksConverter\Loader;
use matcracker\BlocksConverter\utils\Utils;
use pocketmine\block\BaseSign;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\utils\SignText;
use pocketmine\Server;
use pocketmine\utils\Binary;
use pocketmine\utils\TextFormat;
use pocketmine\world\format\Chunk;
use pocketmine\world\format\io\leveldb\LevelDB;
use pocketmine\world\format\io\region\Anvil;
use pocketmine\world\format\io\region\McRegion;
use pocketmine\world\format\io\region\PMAnvil;
use pocketmine\world\format\SubChunk;
use pocketmine\world\World;
use RegexIterator;
use Webmozart\PathUtil\Path;
use function is_array;
use function json_decode;
use function microtime;
use function number_format;
use function sprintf;
use function strlen;
use function substr;
use const PHP_EOL;

class WorldManager{
	private bool $isConverting;
	private string $worldName;

	private int $convertedBlocks;
	private int $convertedSigns;

	public function __construct(private Loader $loader, private World $world){
		$this->worldName = $world->getFolderName();
	}

	public function getWorld() : World{
		return $this->world;
	}

	public function backup() : void{
		$this->loader->getLogger()->debug("Creating a backup of $this->worldName");
		$srcPath = "{$this->loader->getServer()->getDataPath()}/worlds/$this->worldName";
		$destPath = "{$this->loader->getDataFolder()}/backups/$this->worldName";
		Utils::recursiveCopyDirectory($srcPath, $destPath);
		$this->loader->getLogger()->debug("Backup successfully created");
	}

	public function restore() : void{
		$this->loader->getLogger()->debug("Restoring a backup of $this->worldName");
		$srcPath = "{$this->loader->getDataFolder()}/backups/$this->worldName";
		if(!$this->hasBackup()){
			throw new InvalidStateException("This world never gets a backup.");
		}

		$destPath = "{$this->loader->getServer()->getDataPath()}/worlds/$this->worldName";

		Utils::recursiveCopyDirectory($srcPath, $destPath);
		$this->loader->getLogger()->debug("Successfully restored");
	}

	public function hasBackup() : bool{
		return file_exists("{$this->loader->getDataFolder()}/backups/$this->worldName");
	}

	public function unloadLevel() : bool{
		return Server::getInstance()->getWorldManager()->unloadWorld($this->world);
	}

	public function isConverting() : bool{
		return $this->isConverting;
	}

	public function startConversion(bool $toBedrock = true) : void{
		//Conversion report variables
		$status = true;
		$totalChunks = $convertedChunks = $corruptedChunks = 0;
		$this->convertedBlocks = $this->convertedSigns = 0;

		if(!$this->hasBackup()){
			$this->loader->getLogger()->warning("The world \"$this->worldName\" will be converted without a backup.");
		}

		foreach($this->loader->getServer()->getOnlinePlayers() as $player){
			$player->kick("The server is running a world conversion, try to join later.");
		}

		$this->loader->getLogger()->debug("Starting world \"$this->worldName\" conversion...");
		$this->isConverting = true;
		$provider = $this->world->getProvider();
		$blockMap = $toBedrock ? BlocksMap::getJavaMap() : BlocksMap::getBedrockMap();

		$conversionStart = microtime(true);
		try{
			if($provider instanceof LevelDB){
				foreach($provider->getDatabase()->getIterator() as $key => $_){
					if(strlen($key) === 9 and str_ends_with($key, "v")){ //v => LevelDB::TAG_VERSION
						$chunkX = Binary::readLInt(substr($key, 0, 4));
						$chunkZ = Binary::readLInt(substr($key, 4, 4));
						if($this->convertChunk($chunkX, $chunkZ, $blockMap, $toBedrock)){
							$convertedChunks++;
						}
						$totalChunks++;
					}
				}
			}else{
				foreach($this->createRegionIterator() as $region){
					$regionX = (int) $region[1];
					$regionZ = (int) $region[2];
					$rX = $regionX << 5;
					$rZ = $regionZ << 5;
					for($chunkX = $rX; $chunkX < $rX + 32; ++$chunkX){
						for($chunkZ = $rZ; $chunkZ < $rZ + 32; ++$chunkZ){
							if($this->convertChunk($chunkX, $chunkZ, $blockMap, $toBedrock)){
								$convertedChunks++;
							}
							$totalChunks++;
						}
					}
				}
			}
		}catch(Exception $e){
			$this->loader->getLogger()->critical($e);
			$status = false;
		}

		$this->isConverting = false;
		$this->loader->getLogger()->debug("Conversion finished! Printing full report...");

		$report = PHP_EOL . TextFormat::LIGHT_PURPLE . "--- Conversion Report ---" . TextFormat::EOL;
		$report .= TextFormat::AQUA . "Status: " . ($status ? (TextFormat::DARK_GREEN . "Completed") : (TextFormat::RED . "Aborted")) . TextFormat::EOL;
		$report .= TextFormat::AQUA . "World name: " . TextFormat::GREEN . $this->worldName . TextFormat::EOL;
		$report .= TextFormat::AQUA . "Execution time: " . TextFormat::GREEN . number_format((microtime(true) - $conversionStart), 1) . " second(s)" . TextFormat::EOL;
		$report .= TextFormat::AQUA . "Total chunks: " . TextFormat::GREEN . $totalChunks . TextFormat::EOL;
		$report .= TextFormat::AQUA . "Corrupted chunks: " . TextFormat::GREEN . $corruptedChunks . TextFormat::EOL;
		$report .= TextFormat::AQUA . "Chunks converted: " . TextFormat::GREEN . $convertedChunks . TextFormat::EOL;
		$report .= TextFormat::AQUA . "Blocks converted: " . TextFormat::GREEN . $this->convertedBlocks . TextFormat::EOL;
		$report .= TextFormat::AQUA . "Signs converted: " . TextFormat::GREEN . $this->convertedSigns . TextFormat::EOL;
		$report .= TextFormat::LIGHT_PURPLE . "----------";

		$this->loader->getLogger()->info($report);
	}

	/**
	 * @param int   $chunkX
	 * @param int   $chunkZ
	 * @param int[] $blockMap
	 * @param bool  $toBedrock
	 *
	 * @return bool true if the chunk has been converted otherwise false.
	 */
	private function convertChunk(int $chunkX, int $chunkZ, array $blockMap, bool $toBedrock) : bool{
		$chunk = $this->world->loadChunk($chunkX, $chunkZ);

		if($chunk === null){
			$this->loader->getLogger()->debug("Could not load chunk[$chunkX;$chunkZ]");

			return false;
		}

		$hasChanged = false;

		for($y = $this->world->getMinY(); $y < $this->world->getMaxY(); $y++){
			$subChunk = $chunk->getSubChunk($y >> 4);
			if($subChunk->isEmptyFast()){
				continue;
			}

			for($x = 0; $x < Chunk::MAX_SUBCHUNKS; $x++){
				for($z = 0; $z < Chunk::MAX_SUBCHUNKS; $z++){
					$fullBlockId = $subChunk->getFullBlock($x, $y & SubChunk::COORD_MASK, $z);
					if($fullBlockId === BlockLegacyIds::AIR){ //Full block ID of Air is always 0
						continue;
					}

					$block = BlockFactory::getInstance()->fromFullBlock($fullBlockId);

					//At the moment support sign conversion only from java to bedrock
					if($block instanceof BaseSign && $toBedrock){
						$this->loader->getLogger()->debug("Found a chunk[$chunkX;$chunkZ] containing signs...");
						$lines = ["", "", "", ""];

						foreach($block->getText()->getLines() as $row => $line){
							$data = json_decode($line, true);
							if(is_array($data)){
								if(isset($data["extra"])){
									foreach($data["extra"] as $extraData){
										$lines[$row] .= Utils::getTextFormatColors()[($extraData["color"] ?? "black")] . ($extraData["text"] ?? "");
									}
								}
								$lines[$row] .= $data["text"] ?? "";
							}else{
								$lines[$row] = (string) $data;
							}
						}

						$block->setText(new SignText($lines));

						$hasChanged = true;
						$this->convertedSigns++;
					}else{
						if(!isset($blockMap[$fullBlockId])){
							continue;
						}

						$newBlock = BlockFactory::getInstance()->fromFullBlock($blockMap[$fullBlockId]);

						$this->loader->getLogger()->debug(sprintf("Replaced %d:%d (%s) with %d:%d (%s)", $block->getId(), $block->getMeta(), $block->getName(), $newBlock->getId(), $newBlock->getMeta(), $newBlock->getName()));
						$subChunk->setFullBlock($x, $y & SubChunk::COORD_MASK, $z, $blockMap[$fullBlockId]);
						$hasChanged = true;
						$this->convertedBlocks++;
					}
				}
			}
		}

		//Unload the chunk to free the memory.
		if(!$this->world->unloadChunk($chunkX, $chunkZ, true, $hasChanged)){
			$this->loader->getLogger()->debug("Could not unload the chunk[$chunkX;$chunkZ]");
		}

		return $hasChanged;
	}

	private function createRegionIterator() : RegexIterator{
		return new RegexIterator(
			new FilesystemIterator(
				Path::join($this->world->getProvider()->getPath(), "region"),
				FilesystemIterator::CURRENT_AS_PATHNAME | FilesystemIterator::SKIP_DOTS | FilesystemIterator::UNIX_PATHS
			),
			'/\/r\.(-?\d+)\.(-?\d+)\.' . $this->getWorldExtension() . '$/',
			RegexIterator::GET_MATCH
		);
	}

	private function getWorldExtension() : ?string{
		$provider = $this->world->getProvider();
		if($provider instanceof Anvil){
			return "mca";
		}else if($provider instanceof McRegion){
			return "mcr";
		}else if($provider instanceof PMAnvil){
			return "mcapm";
		}

		return null;
	}
}