<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter\translator;

use pocketmine\utils\Binary;
use pocketmine\world\format\io\leveldb\LevelDB;

final class LevelDBWorldTranslator extends WorldTranslator{

	protected function onTranslate() : void{
		/** @var LevelDB $provider */
		$provider = $this->getWorld()->getProvider();

		foreach($provider->getDatabase()->getIterator() as $key => $_){
			if(strlen($key) === 9 and str_ends_with($key, "v")){ //v => LevelDB::TAG_VERSION
				$chunkX = Binary::readLInt(substr($key, 0, 4));
				$chunkZ = Binary::readLInt(substr($key, 4, 4));
				$this->translateChunk($chunkX, $chunkZ);
			}
		}
	}

	protected function getAllowedProviders() : array{
		return [LevelDB::class];
	}
}