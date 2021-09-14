<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter\commands;

use matcracker\BlocksConverter\Loader;
use matcracker\BlocksConverter\world\WorldManager;
use matcracker\BlocksConverter\world\WorldQueue;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat;

final class ConvertQueue extends Command implements PluginOwned{
	private Loader $loader;

	public function __construct(Loader $loader){
		parent::__construct(
			'convertqueue',
			'Allows to add in queue worlds for the conversion.',
			'/convertqueue <add|remove|status> <world_name|all>',
			['cq']
		);
		$this->loader = $loader;
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{
		if(!$sender->hasPermission("blocksconverter.command.convertqueue")){
			$sender->sendMessage(TextFormat::RED . "You don't have permission to run this command!");

			return false;
		}

		if(count($args) < 1 || count($args) > 2 || !isset($args[0])){
			$sender->sendMessage($this->getUsage());

			return false;
		}

		$action = strtolower($args[0]);

		if($action === "status"){
			if(!WorldQueue::isEmpty()){
				$sender->sendMessage(TextFormat::GOLD . "Worlds in queue:");
				$queued = WorldQueue::getQueue();
				foreach($queued as $queue){
					$sender->sendMessage(TextFormat::AQUA . "- " . $queue->getWorld()->getFolderName());
				}
			}else{
				$sender->sendMessage(TextFormat::RED . "The queue is empty!");
			}

			return true;
		}

		if(!isset($args[1])){
			$sender->sendMessage($this->getUsage());

			return false;
		}

		/**@var string[] $worldNames */
		$worldNames = [];
		$worldManager = $this->loader->getServer()->getWorldManager();

		if(strtolower($args[1]) === "all"){
			foreach($worldManager->getWorlds() as $world){
				$worldNames[] = $world->getFolderName();
			}
		}else{
			$worldNames[] = $args[1];
		}

		foreach($worldNames as $worldName){
			if($action === "add"){
				if(!WorldQueue::isInQueue($worldName)){
					if($worldManager->loadWorld($worldName)){
						$world = $worldManager->getWorldByName($worldName);
						if($world !== null){
							WorldQueue::addInQueue(new WorldManager($this->loader, $world));
							$sender->sendMessage(TextFormat::GREEN . "World \"$worldName\" has been added in queue.");
							continue;
						}
					}
					$sender->sendMessage(TextFormat::RED . "World \"$worldName\" isn't loaded or does not exist.");
				}else{
					$sender->sendMessage(TextFormat::RED . "World \"$worldName\" is already in queue!");
				}
			}elseif($action === "remove"){
				if(WorldQueue::isInQueue($worldName)){
					WorldQueue::removeFromQueue($worldName);
					$sender->sendMessage(TextFormat::GREEN . "World \"$worldName\" has been removed from the queue.");
				}else{
					$sender->sendMessage(TextFormat::GREEN . "World \"$worldName\" is not in queue.");
				}
			}
		}

		return true;
	}

	public function getOwningPlugin() : Loader{
		return $this->loader;
	}
}