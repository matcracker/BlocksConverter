<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter\utils;

use pocketmine\block\Block;
use pocketmine\utils\TextFormat;
use ReflectionClass;

final class Utils{

	private function __construct(){
	}

	public static function toFullBlockId(int $blockId, int $blockMeta) : int{
		return ($blockId << Block::INTERNAL_METADATA_BITS) | $blockMeta;
	}

	public static function getTextFormatColors() : array{
		$reflection = new ReflectionClass(TextFormat::class);

		return array_change_key_case($reflection->getConstants(), CASE_LOWER);
	}
}
