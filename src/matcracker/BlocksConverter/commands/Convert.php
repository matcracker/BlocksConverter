<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter\commands;

use matcracker\BlocksConverter\Loader;
use matcracker\BlocksConverter\world\WorldManager;
use matcracker\BlocksConverter\world\WorldQueue;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\level\format\io\BaseLevelProvider;
use pocketmine\level\Level;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;
use function filter_var;
use function strtolower;
use const FILTER_VALIDATE_BOOLEAN;

final class Convert extends Command implements PluginIdentifiableCommand{
	private $loader;

	public function __construct(Loader $loader){
		parent::__construct(
			'convert',
			'Allows to convert immediately a world or all queued worlds.',
			'/convert <world_name|queue> [backup] [plat_dest] [force]'
		);
		$this->loader = $loader;
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{
		if(!$sender->hasPermission("blocksconverter.command.convert")){
			$sender->sendMessage(TextFormat::RED . "You don't have permission to run this command!");

			return false;
		}

		if(count($args) < 1 || count($args) > 4){
			$sender->sendMessage($this->getUsage());

			return false;
		}
		$worldOption = (string) $args[0];
		$backup = isset($args[1]) ? (bool) filter_var($args[1], FILTER_VALIDATE_BOOLEAN) : true;

		if(isset($args[2])){
			switch(strtolower($args[2])){
				case "bedrock":
					$toBedrock = true;
					break;
				case "java":
					$toBedrock = false;
					break;
				default:
					$sender->sendMessage(TextFormat::RED . "The world conversion format is not supported. Choose \"bedrock\" or \"java\"");

					return true;
			}

		}else{
			$toBedrock = true;

		}

		//Force to convert to the specific world format.
		$force = isset($args[3]) ? (bool) filter_var($args[3], FILTER_VALIDATE_BOOLEAN) : false;

		if(strtolower($worldOption) === "queue"){
			if(!WorldQueue::isEmpty()){
				$queued = WorldQueue::getQueue();
				foreach($queued as $queue){
					$this->convert($queue->getWorld(), $sender, $backup, $toBedrock, $force);
				}
			}else{
				$sender->sendMessage(TextFormat::RED . "The queue is empty.");
			}
		}else{
			if($this->loader->getServer()->loadLevel($worldOption)){
				$world = $this->loader->getServer()->getLevelByName($worldOption);
				if($world !== null){
					$this->convert($world, $sender, $backup, $toBedrock, $force);

					return true;
				}
			}
			$sender->sendMessage(TextFormat::RED . "World {$worldOption} isn't loaded or does not exist.");
		}

		return true;
	}

	private function convert(Level $world, CommandSender $sender, bool $backup, bool $toBedrock, bool $force) : void{
		$provider = $world->getProvider();
		$worldName = $world->getFolderName();

		if($provider instanceof BaseLevelProvider && !$force){
			if($provider->getLevelData()->hasTag("BC-converted", ByteTag::class)){
				$isConvertedToBR = (bool) $provider->getLevelData()->getByte("BC-converted");
				if(($isConvertedToBR && $toBedrock) || (!$isConvertedToBR && !$toBedrock)){
					$sender->sendMessage(TextFormat::RED . "The world \"$worldName\" is already converted.");

					return;
				}

			}elseif(!$toBedrock){ //Without the tag consider the world coming from java
				$sender->sendMessage(TextFormat::RED . "The world \"$worldName\" is already converted.");

				return;
			}
		}

		$sender->sendMessage(TextFormat::DARK_AQUA . "This process could takes a lot of time, so don't join or quit the game and wait patently the finish!");

		$manager = new WorldManager($this->loader, $world);
		if($backup){
			$sender->sendMessage(TextFormat::GOLD . "Creating a backup of \"$worldName\"");
			$manager->backup();
			$sender->sendMessage(TextFormat::GREEN . "Backup created successfully!");
		}else{
			$sender->sendMessage(TextFormat::YELLOW . "No backup will be created for the world \"$worldName\"");
		}
		$sender->sendMessage(TextFormat::AQUA . "Starting $worldName's conversion...");
		$manager->startConversion($toBedrock);

		if($provider instanceof BaseLevelProvider){
			$provider->getLevelData()->setByte("BC-converted", (int) $toBedrock);
			$provider->saveLevelData();
		}
	}

	/**
	 * @return Loader
	 */
	public function getPlugin() : Plugin{
		return $this->loader;
	}
}