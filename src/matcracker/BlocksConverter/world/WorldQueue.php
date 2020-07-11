<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter\world;

final class WorldQueue{
	/**@var WorldManager[] $queue */
	private static $queue = [];

	private function __construct(){
	}

	public static function addInQueue(WorldManager $worldManager) : void{
		self::$queue[$worldManager->getWorld()->getFolderName()] = $worldManager;
	}

	public static function isEmpty() : bool{
		return empty(self::$queue);
	}

	public static function removeFromQueue(string $worldName) : void{
		if(self::isInQueue($worldName)){
			unset(self::$queue[$worldName]);
		}
	}

	public static function isInQueue(string $worldName) : bool{
		return isset(self::$queue[$worldName]);
	}

	/**
	 * @return WorldManager[]
	 */
	public static function getQueue() : array{
		return self::$queue;
	}
}