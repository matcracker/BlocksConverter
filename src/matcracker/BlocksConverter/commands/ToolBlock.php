<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter\commands;

use matcracker\BlocksConverter\Main;
use matcracker\BlocksConverter\tasks\ToolBlockTask;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat;
use function count;

final class ToolBlock extends Command implements PluginOwned{
	/**@var Player[] */
	private static array $players = [];

	public function __construct(private Main $plugin){
		parent::__construct(
			"toolblock",
			"Allows to get information about the block you are looking at.",
			"/toolblock"
		);
		$this->setPermission("blocksconverter.command.toolblock");
	}

	/**
	 * @return Player[]
	 */
	public static function getPlayers() : array{
		return self::$players;
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{
		if(!$this->testPermission($sender)){
			return true;
		}

		if(!($sender instanceof Player)){
			$sender->sendMessage(TextFormat::RED . "You must run this command in-game.");

			return true;
		}

		$senderName = $sender->getName();
		if(self::removePlayer($sender)){
			$sender->sendMessage(TextFormat::RED . "ToolBlock disabled.");
		}else{
			self::$players[$senderName] = $sender;
			$sender->sendMessage(TextFormat::GREEN . "ToolBlock enabled.");

			if(count(self::$players) === 1){
				$this->plugin->getScheduler()->scheduleRepeatingTask(new ToolBlockTask(), 5);
			}
		}

		return true;
	}

	public static function removePlayer(Player $player) : bool{
		if(array_key_exists($player->getName(), self::$players)){
			unset(self::$players[$player->getName()]);

			return true;
		}

		return false;
	}

	public function getOwningPlugin() : Main{
		return $this->plugin;
	}
}