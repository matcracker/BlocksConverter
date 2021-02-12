<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter\commands;

use matcracker\BlocksConverter\Loader;
use matcracker\BlocksConverter\tasks\ToolBlockTask;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;
use function count;

final class ToolBlock extends Command implements PluginIdentifiableCommand{
	/**@var Player[] */
	private static $players = [];
	/** @var Loader */
	private $loader;

	public function __construct(Loader $loader){
		parent::__construct(
			'toolblock',
			'Allows to get information about the block you are looking at.',
			'/toolblock'
		);
		$this->loader = $loader;
	}

	/**
	 * @return Player[]
	 */
	public static function getPlayers() : array{
		return self::$players;
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{
		if(!$sender->hasPermission("blocksconverter.command.toolblock")){
			$sender->sendMessage(TextFormat::RED . "You don't have permission to run this command!");

			return false;
		}

		if(!($sender instanceof Player)){
			$sender->sendMessage(TextFormat::RED . "You must run this command in-game.");

			return false;
		}

		$senderName = $sender->getName();
		if(self::removePlayer($sender)){
			$sender->sendMessage(TextFormat::RED . "ToolBlock disabled.");
		}else{
			self::$players[$senderName] = $sender;
			$sender->sendMessage(TextFormat::GREEN . "ToolBlock enabled.");

			if(count(self::$players) === 1){
				$this->loader->getScheduler()->scheduleRepeatingTask(new ToolBlockTask(), 5);
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

	/**
	 * @return Loader
	 */
	public function getPlugin() : Plugin{
		return $this->loader;
	}
}