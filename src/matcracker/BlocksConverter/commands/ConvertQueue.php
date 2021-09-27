<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter\commands;

use matcracker\BlocksConverter\Main;
use matcracker\BlocksConverter\WorldQueue;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\plugin\PluginOwned;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

final class ConvertQueue extends Command implements PluginOwned{

	public function __construct(private Main $plugin){
		parent::__construct(
			"convertqueue",
			"Allows to add in queue worlds for the conversion.",
			"/convertqueue <add|remove|status> <world_name|all>",
			["cq"]
		);
		$this->setPermission("blocksconverter.command.convertqueue");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{
		if(!$this->testPermission($sender)){
			return true;
		}

		if(!($sender instanceof ConsoleCommandSender)){
			$sender->sendMessage(TextFormat::RED . "You must run this command from console.");

			return true;
		}

		if(count($args) < 1 || count($args) > 2){
			throw new InvalidCommandSyntaxException();
		}

		$action = strtolower($args[0]);

		/** @var WorldQueue $worldQueue */
		$worldQueue = WorldQueue::getInstance();

		if($action === "status"){
			if($worldQueue->count($sender) > 0){
				$sender->sendMessage(TextFormat::GOLD . "Worlds in queue:");
				foreach($worldQueue->getAllWorlds($sender) as $worldName => $world){
					$sender->sendMessage(TextFormat::AQUA . "- $worldName");
				}
			}else{
				$sender->sendMessage(TextFormat::RED . "The queue is empty!");
			}

			return true;
		}

		if(!isset($args[1])){
			throw new InvalidCommandSyntaxException();
		}

		$worldManager = Server::getInstance()->getWorldManager();

		if(strtolower($args[1]) === "all"){
			$worlds = $worldManager->getWorlds();
		}else{
			if(!$worldManager->loadWorld($args[1])){
				$sender->sendMessage(TextFormat::RED . "Could not load world \"$args[1]\".");

				return true;
			}

			$world = $worldManager->getWorldByName($args[1]);

			if($world === null){
				$sender->sendMessage(TextFormat::RED . "World \"$args[1]\" is not loaded or does not exist.");

				return true;
			}

			$worlds[] = $world;
		}

		if($action === "add"){
			foreach($worlds as $world){
				$worldName = $world->getFolderName();
				if($worldQueue->add($sender, $world)){
					$sender->sendMessage(TextFormat::GREEN . "World \"$worldName\" has been added in queue.");
				}else{
					$sender->sendMessage(TextFormat::RED . "World \"$worldName\" is already in queue!");
				}
			}

		}elseif($action === "remove"){
			foreach($worlds as $world){
				$worldName = $world->getFolderName();
				if($worldQueue->remove($sender, $world)){
					$sender->sendMessage(TextFormat::GREEN . "World \"$worldName\" has been removed from the queue.");
				}else{
					$sender->sendMessage(TextFormat::GREEN . "World \"$worldName\" is not in queue.");
				}
			}

		}else{
			throw new InvalidCommandSyntaxException();
		}

		return true;
	}

	public function getOwningPlugin() : Main{
		return $this->plugin;
	}
}