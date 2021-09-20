<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter\world;

use pocketmine\plugin\PluginException;
use pocketmine\utils\Binary;
use pocketmine\world\format\io\leveldb\LevelDB;
use function get_class;
use function var_dump;

final class LevelDBWorldTranslator extends WorldTranslator{

	protected function onTranslate() : void{
		$provider = $this->getWorld()->getProvider();
		if(!($provider instanceof LevelDB)){
			throw new PluginException();
		}

		var_dump(get_class($provider->getDatabase()->getIterator()));

		foreach($provider->getDatabase()->getIterator() as $key => $_){
			if(strlen($key) === 9 and str_ends_with($key, "v")){ //v => LevelDB::TAG_VERSION
				$chunkX = Binary::readLInt(substr($key, 0, 4));
				$chunkZ = Binary::readLInt(substr($key, 4, 4));
				$this->translateChunk($chunkX, $chunkZ);
			}
		}
	}
}