<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

final class ToolBlock extends Command
{
	/**@var Player[] $players */
	private static $players = [];

	public function __construct()
	{
		parent::__construct(
			'toolblock',
			'Allows to get information about the block you are looking at.',
			'/toolblock'
		);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args): bool
	{
		if (!($sender instanceof Player)) {
			$sender->sendMessage(TextFormat::RED . "You must run this command in-game.");
			return false;
		}

		$senderName = $sender->getName();
		if (self::removePlayer($sender)) {
			$sender->sendMessage(TextFormat::RED . "ToolBlock disabled.");
		} else {
			self::$players[$senderName] = $sender;
			$sender->sendMessage(TextFormat::GREEN . "ToolBlock enabled.");
		}

		return true;
	}

	/**
	 * @return Player[]
	 */
	public static function getPlayers(): array
	{
		return self::$players;
	}

	public static function removePlayer(Player $player): bool
	{
		if (array_key_exists($player->getName(), self::$players)) {
			unset(self::$players[$player->getName()]);
			return true;
		}
		return false;
	}
}