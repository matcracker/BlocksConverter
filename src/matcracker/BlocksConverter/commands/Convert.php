<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter\commands;

use matcracker\BlocksConverter\Main;
use matcracker\BlocksConverter\translator\maps\RegionBlocksTranslationMap;
use matcracker\BlocksConverter\translator\LevelDBWorldTranslator;
use matcracker\BlocksConverter\translator\RegionWorldTranslator;
use matcracker\BlocksConverter\WorldQueue;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\plugin\PluginOwned;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\world\format\io\data\BaseNbtWorldData;
use pocketmine\world\format\io\leveldb\LevelDB;
use pocketmine\world\format\io\region\RegionWorldProvider;
use function filter_var;
use function strtolower;
use const FILTER_VALIDATE_BOOLEAN;

final class Convert extends Command implements PluginOwned{

	public function __construct(private Main $plugin){
		parent::__construct(
			"convert",
			"Allows to convert immediately a world or all queued worlds.",
			"/convert <world_name|queue> [backup] [force]"
		);
		$this->setPermission("blocksconverter.command.convert");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{
		if(!$this->testPermission($sender)){
			return true;
		}

		if(!($sender instanceof ConsoleCommandSender)){
			$sender->sendMessage(TextFormat::RED . "You must run this command from console.");

			return true;
		}

		if(count($args) < 1 || count($args) > 4){
			throw new InvalidCommandSyntaxException();
		}

		$worldOption = (string) $args[0];
		$backup = !isset($args[1]) || filter_var($args[1], FILTER_VALIDATE_BOOLEAN);

		//Force converting to the specific world format.
		$force = isset($args[3]) && filter_var($args[3], FILTER_VALIDATE_BOOLEAN);

		if(strtolower($worldOption) === "queue"){
			/** @var WorldQueue $worldQueue */
			$worldQueue = WorldQueue::getInstance();
			if($worldQueue->count($sender) === 0){
				$this->plugin->getLogger()->info("The queue is empty.");

				return true;
			}

			$worlds = $worldQueue->getAllWorlds($sender);
		}else{
			$worldManager = Server::getInstance()->getWorldManager();
			if(!$worldManager->loadWorld($worldOption)){
				$this->plugin->getLogger()->warning("World \"$worldOption\" isn't loaded or does not exist.");

				return true;
			}

			$world = $worldManager->getWorldByName($worldOption);
			if($world === null){
				$this->plugin->getLogger()->warning("World \"$worldOption\" isn't loaded or does not exist.");

				return true;
			}

			$worlds[] = $world;
		}

		$translationMap = new RegionBlocksTranslationMap();

		foreach($worlds as $world){
			$provider = $world->getProvider();
			$worldName = $world->getFolderName();

			if($provider instanceof LevelDB){
				$translator = new LevelDBWorldTranslator($this->plugin, $sender, $world, $translationMap);
			}elseif($provider instanceof RegionWorldProvider){
				$translator = new RegionWorldTranslator($this->plugin, $sender, $world, $translationMap);
			}else{
				//TODO
				continue;
			}

			$worldData = $provider->getWorldData();
			if(!($worldData instanceof BaseNbtWorldData)){
				//TODO
				continue;
			}

			if(!$force){
				$translated = (bool) $worldData->getCompoundTag()->getCompoundTag("BlocksConverter")?->getByte("translated", 0);
				if($translated){
					$this->plugin->getLogger()->notice("The world \"$worldName\" is already converted.");
					continue;
				}
			}

			$this->plugin->getLogger()->notice("This process could takes a lot of time, so don't join or quit the game and wait patently the finish!");

			if($backup){
				$this->plugin->getLogger()->info("Creating a backup for the world \"$worldName\"");
				if($translator->backup()){
					$this->plugin->getLogger()->notice("Backup successfully created for the world \"$worldName\".");
				}else{
					$this->plugin->getLogger()->warning("Could not create a backup for the world \"$worldName\"");

					return true;
				}
			}else{
				$this->plugin->getLogger()->info("No backup will be created for the world \"$worldName\"");
			}

			$this->plugin->getLogger()->info("Converting the world \"$worldName\".");
			$translator->translate()->printReport();

			$worldData->getCompoundTag()->setTag(
				"BlocksConverter",
				(new CompoundTag())->setByte("translated", 1)
			);
		}

		return true;
	}

	public function getOwningPlugin() : Main{
		return $this->plugin;
	}
}