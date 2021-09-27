<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter\translator;

use FilesystemIterator;
use pocketmine\world\format\io\region\Anvil;
use pocketmine\world\format\io\region\McRegion;
use pocketmine\world\format\io\region\PMAnvil;
use RegexIterator;
use Webmozart\PathUtil\Path;

final class RegionWorldTranslator extends WorldTranslator{

	protected function onTranslate() : void{
		foreach($this->createRegionIterator() as [$regionX, $regionZ]){
			$rX = $regionX << 5;
			$rZ = $regionZ << 5;
			for($chunkX = $rX; $chunkX < $rX + 32; ++$chunkX){
				for($chunkZ = $rZ; $chunkZ < $rZ + 32; ++$chunkZ){
					$this->translateChunk($chunkX, $chunkZ);
				}
			}
		}
	}

	private function createRegionIterator() : RegexIterator{
		return new RegexIterator(
			new FilesystemIterator(
				Path::join($this->getWorld()->getProvider()->getPath(), "region"),
				FilesystemIterator::CURRENT_AS_PATHNAME | FilesystemIterator::SKIP_DOTS | FilesystemIterator::UNIX_PATHS
			),
			'/\/r\.(-?\d+)\.(-?\d+)\.' . $this->getWorldExtension() . '$/',
			RegexIterator::GET_MATCH
		);
	}

	private function getWorldExtension() : string{
		$provider = $this->getWorld()->getProvider();
		if($provider instanceof Anvil){
			return "mca";
		}else if($provider instanceof McRegion){
			return "mcr";
		}else{
			return "mcapm";
		}
	}

	protected function getAllowedProviders() : array{
		return [
			Anvil::class, McRegion::class, PMAnvil::class
		];
	}
}