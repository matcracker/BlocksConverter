<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter\world;

use Exception;
use matcracker\BlocksConverter\Main;
use matcracker\BlocksConverter\translationMaps\BlocksTranslationMap;
use matcracker\BlocksConverter\utils\Utils;
use pocketmine\block\BaseSign;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\utils\SignText;
use pocketmine\plugin\PluginException;
use pocketmine\Server;
use pocketmine\utils\Filesystem;
use pocketmine\utils\TextFormat;
use pocketmine\world\format\Chunk;
use pocketmine\world\format\SubChunk;
use pocketmine\world\World;
use Webmozart\PathUtil\Path;
use function file_exists;
use function is_array;
use function json_decode;
use function microtime;
use function mkdir;
use function sprintf;

abstract class WorldTranslator{

	protected string $worldName;

	private bool $running;
	private bool $executed;

	private bool $error;

	private int $totalChunks = 0;
	private int $translatedChunks = 0;
	private int $translatedBlocks = 0;
	private int $translatedSigns = 0;
	private int $corruptedChunks = 0;
	private int $executionTime = 0;

	public function __construct(
		private Main $plugin,
		private World $world,
		private BlocksTranslationMap $translationMap){

		$this->worldName = $this->world->getFolderName();
	}

	public function getWorld() : World{
		return $this->world;
	}

	public function backup() : bool{
		if(!Server::getInstance()->getWorldManager()->unloadWorld($this->world, true)){
			return false;
		}


		$srcPath = Path::join(Server::getInstance()->getDataPath(), "worlds", $this->worldName);

		do{
			$backupPath = Path::join($this->plugin->getDataFolder(), "backups", "{$this->worldName}_" . (int) microtime(true));
		}while(file_exists($backupPath));

		@mkdir($backupPath, 0777, true);

		Filesystem::recursiveCopy($srcPath, $backupPath);


		if(!Server::getInstance()->getWorldManager()->loadWorld($this->worldName)){
			$this->plugin->getLogger()->warning("Could not load world \"$this->worldName\"");

			return false;
		}

		return true;
	}

	public function isRunning() : bool{
		return $this->running;
	}

	public function hasBeenExecuted() : bool{
		return $this->executed;
	}

	public function translate() : self{
		$this->error = false;
		$this->executed = false;
		$this->running = true;

		$this->plugin->getLogger()->info("Starting world \"$this->worldName\" conversion.");

		foreach($this->world->getPlayers() as $player){
			$player->kick("The server is running a world conversion, try to join later.");
		}

		$startTime = microtime(true);

		try{
			$this->onTranslate();
		}catch(Exception $e){
			$this->plugin->getLogger()->critical($e);
			$this->error = true;
		}

		$this->executionTime = (int) (microtime(true) - $startTime);

		$this->running = false;
		$this->executed = true;

		return $this;
	}

	abstract protected function onTranslate() : void;

	public function printReport() : self{
		if(!$this->executed){
			throw new PluginException("Could not print report before the execution.");
		}

		$this->plugin->getLogger()->info(TextFormat::LIGHT_PURPLE . "--- Conversion Report ---");
		$this->plugin->getLogger()->info(TextFormat::AQUA . "Status: " . (!$this->error ? TextFormat::DARK_GREEN . "Completed" : TextFormat::RED . "Aborted"));
		$this->plugin->getLogger()->info(TextFormat::AQUA . "World name: " . TextFormat::GREEN . $this->worldName);
		$this->plugin->getLogger()->info(TextFormat::AQUA . "Execution time: " . TextFormat::GREEN . "$this->executionTime second(s)");
		$this->plugin->getLogger()->info(TextFormat::AQUA . "Total chunks: " . TextFormat::GREEN . $this->totalChunks);
		$this->plugin->getLogger()->info(TextFormat::AQUA . "Corrupted chunks: " . TextFormat::GREEN . $this->corruptedChunks);
		$this->plugin->getLogger()->info(TextFormat::AQUA . "Chunks converted: " . TextFormat::GREEN . $this->translatedChunks);
		$this->plugin->getLogger()->info(TextFormat::AQUA . "Blocks converted: " . TextFormat::GREEN . $this->translatedBlocks);
		$this->plugin->getLogger()->info(TextFormat::AQUA . "Signs converted: " . TextFormat::GREEN . $this->translatedSigns);
		$this->plugin->getLogger()->info(TextFormat::LIGHT_PURPLE . "----------");

		return $this;
	}

	public function getTotalChunks() : int{
		return $this->totalChunks;
	}

	public function getExecutionTime() : int{
		return $this->executionTime;
	}

	public function getTranslatedBlocks() : int{
		return $this->translatedBlocks;
	}

	public function getTranslatedChunks() : int{
		return $this->translatedChunks;
	}

	public function getTranslatedSigns() : int{
		return $this->translatedSigns;
	}

	public function getCorruptedChunks() : int{
		return $this->corruptedChunks;
	}

	public function hasErrors() : bool{
		return $this->error;
	}

	final protected function translateChunk(int $chunkX, int $chunkZ) : void{
		$chunk = $this->world->loadChunk($chunkX, $chunkZ);

		if($chunk === null){
			$this->plugin->getLogger()->debug("Could not load chunk $chunkX $chunkZ.");
			$this->corruptedChunks++;

			return;
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
					if($block instanceof BaseSign){
						$this->plugin->getLogger()->debug("Found a chunk[$chunkX;$chunkZ] containing signs...");
						$lines = ["", "", "", ""];

						foreach($block->getText()->getLines() as $index => $line){
							$data = json_decode($line, true);
							if(is_array($data)){
								if(isset($data["extra"])){
									foreach($data["extra"] as $extraData){
										$lines[$index] .= Utils::getTextFormatColors()[($extraData["color"] ?? "black")] . ($extraData["text"] ?? "");
									}
								}
								$lines[$index] .= $data["text"] ?? "";
							}else{
								$lines[$index] = (string) $data;
							}
						}

						$block->setText(new SignText($lines));

						$hasChanged = true;
						$this->translatedSigns++;
					}else{
						if(!isset($this->translationMap[$fullBlockId])){
							continue;
						}

						$newBlock = BlockFactory::getInstance()->fromFullBlock($this->translationMap[$fullBlockId]);

						$this->plugin->getLogger()->debug(sprintf("Replaced %d:%d (%s) with %d:%d (%s)", $block->getId(), $block->getMeta(), $block->getName(), $newBlock->getId(), $newBlock->getMeta(), $newBlock->getName()));
						$subChunk->setFullBlock($x, $y & SubChunk::COORD_MASK, $z, $this->translationMap[$fullBlockId]);
						$hasChanged = true;
						$this->translatedBlocks++;
					}
				}
			}
		}

		//Unload the chunk to free the memory.
		if(!$this->world->unloadChunk($chunkX, $chunkZ, true, $hasChanged)){
			$this->plugin->getLogger()->debug("Could not unload the chunk[$chunkX;$chunkZ]");
		}

		if($hasChanged){
			$this->translatedChunks++;
		}

		$this->totalChunks++;
	}


}