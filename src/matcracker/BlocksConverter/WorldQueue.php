<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter;

use pocketmine\command\CommandSender;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\World;
use function count;

final class WorldQueue{
	use SingletonTrait;

	/**@var array<string, array<string, World> $queue */
	private array $queue = [];

	public function add(CommandSender $sender, World $world) : bool{
		if(!$this->exist($sender, $world)){
			$this->queue[$sender->getName()][$world->getFolderName()] = $world;

			return true;
		}

		return false;
	}

	public function count(CommandSender $sender) : int{
		return count($this->queue[$sender->getName()] ?? []);
	}

	public function remove(CommandSender $sender, World $world) : bool{
		if($this->exist($sender, $world)){
			unset($this->queue[$sender->getName()][$world->getFolderName()]);

			return true;
		}

		return false;
	}

	public function exist(CommandSender $sender, World $world) : bool{
		return isset($this->queue[$sender->getName()][$world->getFolderName()]);
	}

	/**
	 * @return array<string, World>
	 */
	public function getAllWorlds(CommandSender $sender) : array{
		return $this->queue[$sender->getName()];
	}

	public function getWorld(CommandSender $sender, string $worldName) : World{
		return $this->queue[$sender->getName()][$worldName];
	}
}