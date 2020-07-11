<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter\commands;

use matcracker\BlocksConverter\Loader;
use matcracker\BlocksConverter\world\WorldManager;
use matcracker\BlocksConverter\world\WorldQueue;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

final class Convert extends Command implements PluginIdentifiableCommand{
	private $loader;

	public function __construct(Loader $loader){
		parent::__construct(
			'convert',
			'Allows to convert immediately a world or all queued worlds.',
			'/convert <world_name|queue> [backup]'
		);
		$this->loader = $loader;
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{
		if(!$sender->hasPermission("blocksconverter.command.convert")){
			$sender->sendMessage(TextFormat::RED . "You don't have permission to run this command!");

			return false;
		}

		if(count($args) < 1 || count($args) > 2){
			$sender->sendMessage($this->getUsage());

			return false;
		}
		$worldOption = (string) $args[0];
		$backup = isset($args[1]) ? (bool) filter_var($args[1], FILTER_VALIDATE_BOOLEAN) : true;

		if(strtolower($worldOption) === "queue"){
			if(!WorldQueue::isEmpty()){
				$sender->sendMessage(TextFormat::DARK_AQUA . "This process could takes a lot of time, so don't join or quit the game and wait patently the finish!");
				$queued = WorldQueue::getQueue();
				foreach($queued as $queue){
					$worldName = $queue->getWorld()->getFolderName();
					if($backup){
						$sender->sendMessage(TextFormat::GOLD . "Creating a backup of {$worldName}");
						$queue->backup();
						$sender->sendMessage(TextFormat::GREEN . "Backup created successfully!");
					}else{
						$sender->sendMessage(TextFormat::YELLOW . "No backup will be created for the world {$worldName}");
					}
					$sender->sendMessage(TextFormat::AQUA . "Starting the {$worldOption}'s conversion...");
					$queue->startConversion();
				}
			}else{
				$sender->sendMessage(TextFormat::RED . "The queue is empty.");
			}
		}else{
			if($this->loader->getServer()->loadLevel($worldOption)){
				$world = $this->loader->getServer()->getLevelByName($worldOption);
				if($world !== null){
					$sender->sendMessage(TextFormat::DARK_AQUA . "This process could takes a some time, so don't join or quit the game and wait patently the finish!");
					$manager = new WorldManager($this->loader, $world);
					if($backup){
						$sender->sendMessage(TextFormat::GOLD . "Creating a backup of {$worldOption}");
						$manager->backup();
						$sender->sendMessage(TextFormat::GREEN . "Backup created successfully!");
					}else{
						$sender->sendMessage(TextFormat::YELLOW . "No backup will be created for the world {$worldOption}");
					}
					$sender->sendMessage(TextFormat::AQUA . "Starting the {$worldOption}'s conversion...");
					$manager->startConversion();

					return true;
				}
			}
			$sender->sendMessage(TextFormat::RED . "World {$worldOption} isn't loaded or does not exist.");
		}

		return true;
	}

	/**
	 * @return Loader
	 */
	public function getPlugin() : Plugin{
		return $this->loader;
	}
}