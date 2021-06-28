<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter\world;

use Exception;
use FilesystemIterator;
use InvalidStateException;
use matcracker\BlocksConverter\BlocksMap;
use matcracker\BlocksConverter\Loader;
use matcracker\BlocksConverter\utils\Utils;
use pocketmine\block\BlockLegacyIds as BlockIds;
use pocketmine\block\BlockFactory;
use pocketmine\world\format\Chunk;
use pocketmine\world\format\EmptySubChunk;
use pocketmine\world\format\io\exception\CorruptedChunkException;
use pocketmine\world\format\io\leveldb\LevelDB;
use pocketmine\world\format\io\region\Anvil;
use pocketmine\world\format\io\region\McRegion;
use pocketmine\world\format\io\region\PMAnvil;
use pocketmine\world\World;
use pocketmine\tile\Sign;
use pocketmine\utils\Binary;
use pocketmine\utils\TextFormat;
use RegexIterator;
use function is_array;
use function json_decode;
use function microtime;
use function number_format;
use function strlen;
use function substr;
use const PHP_EOL;

class WorldManager{
	/**@var Loader */
	private $loader;
	/**@var Level */
	private $world;
	/**@var bool */
	private $isConverting = false;
	/** @var string */
	private $worldName;

	private $convertedBlocks = 0;
	private $convertedSigns = 0;

	public function __construct(Loader $loader, World $world){
		$this->loader = $loader;
		$this->world = $world;
		$this->worldName = $world->getFolderName();
	}

	public function getWorld() : World{
		return $this->world;
	}

	public function backup() : void{
		$this->loader->getLogger()->debug("Creating a backup of {$this->worldName}");
		$srcPath = "{$this->loader->getServer()->getDataPath()}/worlds/{$this->worldName}";
		$destPath = "{$this->loader->getDataFolder()}/backups/{$this->worldName}";
		Utils::recursiveCopyDirectory($srcPath, $destPath);
		$this->loader->getLogger()->debug("Backup successfully created");
	}

	public function restore() : void{
		$this->loader->getLogger()->debug("Restoring a backup of {$this->worldName}");
		$srcPath = "{$this->loader->getDataFolder()}/backups/{$this->worldName}";
		if(!$this->hasBackup()){
			throw new InvalidStateException("This world never gets a backup.");
		}

		$destPath = "{$this->loader->getServer()->getDataPath()}/worlds/{$this->worldName}";

		Utils::recursiveCopyDirectory($srcPath, $destPath);
		$this->loader->getLogger()->debug("Successfully restored");
	}

	public function hasBackup() : bool{
		return file_exists("{$this->loader->getDataFolder()}/backups/{$this->worldName}");
	}

	public function unloadLevel() : bool{
		return $this->loader->getServer()->unloadLevel($this->world);
	}

	public function isConverting() : bool{
		return $this->isConverting;
	}

	public function startConversion(bool $toBedrock = true) : void{
		//Conversion report variables
		$status = true;
		$totalChunks = $convertedChunks = $unloadedChunks = $corruptedChunks = 0;
		$this->convertedBlocks = $this->convertedSigns = 0;

		if(!$this->hasBackup()){
			$this->loader->getLogger()->warning("The world \"{$this->worldName}\" will be converted without a backup.");
		}

		foreach($this->loader->getServer()->getOnlinePlayers() as $player){
			$player->kick("The server is running a world conversion, try to join later.", false);
		}

		$this->loader->getLogger()->debug("Starting world \"{$this->worldName}\" conversion...");
		$this->isConverting = true;
		$provider = $this->world->getProvider();

		$conversionStart = microtime(true);
		try{
			if($provider instanceof LevelDB){
				foreach ($provider->getAllChunks(true) as $coords => $chunk) {
					[$chunkX, $chunkZ] = $coords;
					if($this->convertChunk($chunkX, $chunkZ, $chunk, $toBedrock)){
						$convertedChunks++;
					}
					$totalChunks++;
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
	 * @param Chunk $chunk
	 * @param bool  $toBedrock
	 *
	 * @return bool true if the chunk has been converted otherwise false.
	 */
	private function convertChunk(int $chunkX, int $chunkZ, Chunk $chunk, bool $toBedrock = true) : bool{
		$hasChanged = false;
		//$cx = $chunk->getX();
		//$cz = $chunk->getZ();
		$signChunkConverted = false;

		$blockMap = $toBedrock ? BlocksMap::get() : BlocksMap::reverse();
		$blockFac = BlockFactory::getInstance();

		for($y = 0; $y < 255; $y++){
			$subChunk = $chunk->getSubChunk($y >> 4);
			if($subChunk instanceof EmptySubChunk){
				continue;
			}

			for($x = 0; $x < 16; $x++){
				for($z = 0; $z < 16; $z++){
					$fullBlock = $subChunk->getFullBlock($x, $y & 0x0f, $z);
					$block = $blockFac->fromFullBlock($fullBlock);
					$blockId = $block->getId();
					if($blockId === BlockIds::AIR){
						continue;
					}

					//At the moment support sign conversion only from java to bedrock
					if(($blockId === BlockIds::SIGN_POST || $blockId === BlockIds::WALL_SIGN) && $toBedrock){
						if($signChunkConverted){
							continue;
						}

						$tiles = $chunk->getTiles();
						foreach($tiles as $tile){
							if(!$tile instanceof Sign){
								continue;
							}

							for($i = 0; $i < 4; $i++){
								$line = "";
								$data = json_decode($tile->getLine($i), true);
								if(is_array($data)){
									if(isset($data["extra"])){
										foreach($data["extra"] as $extraData){
											$line .= Utils::getTextFormatColors()[($extraData["color"] ?? "black")] . ($extraData["text"] ?? "");
										}
									}
									$line .= $data["text"] ?? "";
								}else{
									$line = (string) $data;
								}
								$tile->setLine($i, $line);
							}

							$hasChanged = true;
							$this->convertedSigns++;
						}
						$signChunkConverted = true;

					}else{
						$blockMeta = $block->getMeta();

						if(!isset($blockMap[$blockId][$blockMeta])){
							continue;
						}

						$subMap = $blockMap[$blockId][$blockMeta];
						$this->loader->getLogger()->info("Replaced block \"{$blockId}:{$blockMeta}\" with \"{$subMap[0]}:{$subMap[1]}\"");
						$subChunk->setFullBlock($x, $y & 0x0f, $z, ($subMap[0] << 4) | $subMap[1]);
						$hasChanged = true;
						$this->convertedBlocks++;
					}
				}
			}
		}

		if($hasChanged){
			//Marking the chunk as changed, so it can be saved after the conversion.
			$this->world->getProvider()->saveChunk($chunkX, $chunkZ, $chunk);

			//Unload the chunk to free the memory.
			/*if(!$this->world->unloadChunk($cx, $cz)){
				$this->loader->getLogger()->debug("Could not unload the chunk[{$cx};{$cz}]");
			}*/
		}

		return $hasChanged;
	}

	/*private function createRegionIterator() : RegexIterator{
		return new RegexIterator(
			new FilesystemIterator(
				$this->world->getProvider()->getPath() . 'region/',
				FilesystemIterator::CURRENT_AS_PATHNAME | FilesystemIterator::SKIP_DOTS | FilesystemIterator::UNIX_PATHS
			),
			'/\/r\.(-?\d+)\.(-?\d+)\.' . $this->getWorldExtension() . '$/',
			RegexIterator::GET_MATCH
		);
	}

	private function getWorldExtension() : ?string{
		$providerName = $this->world->getProvider()->getProviderName();
		if($providerName === Anvil::getProviderName()){
			return Anvil::REGION_FILE_EXTENSION;
		}else if($providerName === McRegion::getProviderName()){
			return McRegion::REGION_FILE_EXTENSION;
		}else if($providerName === PMAnvil::getProviderName()){
			return PMAnvil::REGION_FILE_EXTENSION;
		}

		return null;
	}

	private function countChunks() : int{
		$provider = $this->world->getProvider();
		$count = 0;
		if($provider instanceof LevelDB){
			foreach($provider->getDatabase()->getIterator() as $key => $_){
				if(strlen($key) === 9 && substr($key, -1) === LevelDB::TAG_VERSION){
					$count++;
				}
			}
		}else{
			foreach($this->createRegionIterator() as $region){
				$regionX = ((int) $region[1]);
				$regionZ = ((int) $region[2]);
				$rX = $regionX << 5;
				$rZ = $regionZ << 5;
				for($chunkX = $rX; $chunkX < $rX + 32; ++$chunkX){
					for($chunkZ = $rZ; $chunkZ < $rZ + 32; ++$chunkZ){
						if($this->world->isChunkGenerated($chunkX, $chunkZ)){
							$this->world->unloadChunk($chunkX, $chunkZ, false, false);
							$count++;
						}
					}
				}
			}
		}

		return $count;
	}*/
}