<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter\translator;

use Exception;
use matcracker\BlocksConverter\Main;
use matcracker\BlocksConverter\translator\maps\BlocksTranslationMap;
use matcracker\BlocksConverter\utils\Utils;
use pocketmine\block\Air;
use pocketmine\block\BaseSign;
use pocketmine\block\BlockFactory;
use pocketmine\block\utils\SignText;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginException;
use pocketmine\Server;
use pocketmine\utils\Filesystem;
use pocketmine\utils\TextFormat;
use pocketmine\world\format\Chunk;
use pocketmine\world\format\SubChunk;
use pocketmine\world\World;
use Webmozart\PathUtil\Path;
use function file_exists;
use function get_class;
use function in_array;
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
		private CommandSender $sender,
		private World $world,
		private BlocksTranslationMap $translationMap){

		$this->worldName = $this->world->getFolderName();

		if(!in_array(get_class($this->world->getProvider()), $this->getAllowedProviders())){
			throw new PluginException("Unsupported world provider");
		}
	}

	/**
	 * @return array<mixed, string>
	 */
	abstract protected function getAllowedProviders() : array;

	final public function getWorld() : World{
		return $this->world;
	}

	public function backup() : bool{
		$worldManager = Server::getInstance()->getWorldManager();
		$isDefault = $worldManager->getDefaultWorld()?->getFolderName() === $this->worldName;

		if(!$worldManager->unloadWorld($this->world, true)){
			return false;
		}

		$srcPath = Path::join(Server::getInstance()->getDataPath(), "worlds", $this->worldName);

		do{
			$backupPath = Path::join($this->plugin->getDataFolder(), "backups", "{$this->worldName}_" . (int) microtime(true));
		}while(file_exists($backupPath));

		@mkdir($backupPath, 0777, true);

		Filesystem::recursiveCopy($srcPath, $backupPath);

		if(!$worldManager->loadWorld($this->worldName)){
			$this->plugin->getLogger()->warning("Could not load world \"$this->worldName\"");

			return false;
		}

		$this->world = $worldManager->getWorldByName($this->worldName);
		if($this->world === null){
			throw new PluginException();
		}

		if($isDefault){
			$worldManager->setDefaultWorld($this->world);
		}

		return true;
	}

	final public function isRunning() : bool{
		return $this->running;
	}

	final public function hasBeenExecuted() : bool{
		return $this->executed;
	}

	final public function translate() : self{
		$this->error = false;
		$this->executed = false;
		$this->running = true;

		$this->sender->sendMessage("Starting world \"$this->worldName\" conversion.");

		foreach(Server::getInstance()->getOnlinePlayers() as $player){
			$player->kick("The server is running a world conversion, try to join later.");
		}

		$startTime = microtime(true);

		try{
			$this->onTranslate();
		}catch(Exception $e){
			$this->sender->sendMessage(TextFormat::RED . $e->__toString());
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

		$this->sender->sendMessage(TextFormat::LIGHT_PURPLE . "--- Conversion Report ---");
		$this->sender->sendMessage(TextFormat::AQUA . "Status: " . (!$this->error ? TextFormat::DARK_GREEN . "Completed" : TextFormat::RED . "Aborted"));
		$this->sender->sendMessage(TextFormat::AQUA . "World name: " . TextFormat::GREEN . $this->worldName);
		$this->sender->sendMessage(TextFormat::AQUA . "Execution time: " . TextFormat::GREEN . "$this->executionTime second(s)");
		$this->sender->sendMessage(TextFormat::AQUA . "Total chunks: " . TextFormat::GREEN . $this->totalChunks);
		$this->sender->sendMessage(TextFormat::AQUA . "Corrupted chunks: " . TextFormat::GREEN . $this->corruptedChunks);
		$this->sender->sendMessage(TextFormat::AQUA . "Chunks converted: " . TextFormat::GREEN . $this->translatedChunks);
		$this->sender->sendMessage(TextFormat::AQUA . "Blocks converted: " . TextFormat::GREEN . $this->translatedBlocks);
		$this->sender->sendMessage(TextFormat::AQUA . "Signs converted: " . TextFormat::GREEN . $this->translatedSigns);
		$this->sender->sendMessage(TextFormat::LIGHT_PURPLE . "----------");

		return $this;
	}

	final public function getTotalChunks() : int{
		return $this->totalChunks;
	}

	final public function getExecutionTime() : int{
		return $this->executionTime;
	}

	final public function getTranslatedBlocks() : int{
		return $this->translatedBlocks;
	}

	final public function getTranslatedChunks() : int{
		return $this->translatedChunks;
	}

	final public function getTranslatedSigns() : int{
		return $this->translatedSigns;
	}

	final public function getCorruptedChunks() : int{
		return $this->corruptedChunks;
	}

	final public function hasErrors() : bool{
		return $this->error;
	}

	final protected function getSender() : CommandSender{
		return $this->sender;
	}

	final protected function translateChunk(int $chunkX, int $chunkZ) : void{
		$chunk = $this->world->loadChunk($chunkX, $chunkZ);

		if($chunk === null){
			$this->plugin->getLogger()->debug("Could not load chunk $chunkX $chunkZ.");
			$this->corruptedChunks++;

			return;
		}

		$hasChanged = false;
		/** @var BlockFactory $factory */
		$factory = BlockFactory::getInstance();

		for($y = $this->world->getMinY(); $y < $this->world->getMaxY(); $y++){
			$subChunk = $chunk->getSubChunk($y >> SubChunk::COORD_BIT_SIZE);
			if($subChunk->isEmptyAuthoritative()){
				continue;
			}

			$cx = $chunkX << Chunk::COORD_BIT_SIZE;
			$cz = $chunkZ << Chunk::COORD_BIT_SIZE;
			$maxChunkX = $cx + Chunk::MAX_SUBCHUNKS;
			$maxChunkZ = $cz + Chunk::MAX_SUBCHUNKS;

			for($x = $cx; $x < $maxChunkX; $x++){
				for($z = $cz; $z < $maxChunkZ; $z++){
					$oldBlock = $this->world->getBlockAt($x, $y, $z, addToCache: false);
					if($oldBlock instanceof Air){
						continue;
					}

					//At the moment support sign conversion only from java to bedrock
					if($oldBlock instanceof BaseSign){
						$this->plugin->getLogger()->debug("Found a chunk[$chunkX;$chunkZ] containing signs...");
						$lines = ["", "", "", ""];

						foreach($oldBlock->getText()->getLines() as $index => $line){
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

						$oldBlock->setText(new SignText($lines));
						$oldBlock->writeStateToWorld();

						$hasChanged = true;
						$this->translatedSigns++;
					}else{
						$oldId = $oldBlock->getId();
						$oldMeta = $oldBlock->getMeta();
						if(!isset($this->translationMap[$oldId][$oldMeta])){
							continue;
						}

						[$newId, $newMeta] = $this->translationMap[$oldId][$oldMeta];

						$newBlock = $factory->get($newId, $newMeta);

						$this->plugin->getLogger()->debug(sprintf("Replaced %d:%d (%s) with %d:%d (%s)", $oldId, $oldMeta, $oldBlock->getName(), $newId, $newMeta, $newBlock->getName()));
						$this->world->setBlockAt($x, $y, $z, $newBlock, false);
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