<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter\world;

use pocketmine\world\World;
use function count;

final class WorldQueue{

	/**@var World[] $queue */
	private static array $queue = [];

	private function __construct(){
	}

	public static function add(World $world) : void{
		self::$queue[$world->getFolderName()] = $world;
	}

	public static function isEmpty() : bool{
		return count(self::$queue) === 0;
	}

	public static function remove(World $world) : void{
		if(self::isPresent($world)){
			unset(self::$queue[$world->getFolderName()]);
		}
	}

	public static function isPresent(World $world) : bool{
		return isset(self::$queue[$world->getFolderName()]);
	}

	/**
	 * @return World[]
	 */
	public static function getAll() : array{
		return self::$queue;
	}

	public static function get(string $worldName) : World{
		return self::$queue[$worldName];
	}
}