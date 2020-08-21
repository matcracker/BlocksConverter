<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter;

use pocketmine\block\BlockIds;

final class BlocksMap{
	private static $MAP = [
		BlockIds::DOUBLE_STONE_SLAB => [
			6 => [BlockIds::DOUBLE_STONE_SLAB, 7],
			7 => [BlockIds::DOUBLE_STONE_SLAB, 6]
		],
		BlockIds::STONE_SLAB => [
			1 => [BlockIds::DROPPER, 0],
			6 => [BlockIds::STONE_SLAB, 7],
			7 => [BlockIds::STONE_SLAB, 6],
			14 => [BlockIds::STONE_SLAB, 15],
			15 => [BlockIds::STONE_SLAB, 14]
		],
		BlockIds::TRAPDOOR => [
			0 => [BlockIds::TRAPDOOR, 3],
			1 => [BlockIds::TRAPDOOR, 2],
			2 => [BlockIds::TRAPDOOR, 1],
			3 => [BlockIds::TRAPDOOR, 0],
			4 => [BlockIds::TRAPDOOR, 11],
			5 => [BlockIds::TRAPDOOR, 10],
			6 => [BlockIds::TRAPDOOR, 9],
			7 => [BlockIds::TRAPDOOR, 8],
			8 => [BlockIds::TRAPDOOR, 7],
			9 => [BlockIds::TRAPDOOR, 6],
			10 => [BlockIds::TRAPDOOR, 5],
			11 => [BlockIds::TRAPDOOR, 4],
			12 => [BlockIds::TRAPDOOR, 15],
			13 => [BlockIds::TRAPDOOR, 14],
			14 => [BlockIds::TRAPDOOR, 13],
			15 => [BlockIds::TRAPDOOR, 12]
		],
		BlockIds::QUARTZ_BLOCK => [
			3 => [BlockIds::QUARTZ_BLOCK, 6],
			4 => [BlockIds::QUARTZ_BLOCK, 10]
		],
		BlockIds::IRON_TRAPDOOR => [
			0 => [BlockIds::IRON_TRAPDOOR, 3],
			1 => [BlockIds::IRON_TRAPDOOR, 2],
			2 => [BlockIds::IRON_TRAPDOOR, 1],
			3 => [BlockIds::IRON_TRAPDOOR, 0],
			4 => [BlockIds::IRON_TRAPDOOR, 11],
			5 => [BlockIds::IRON_TRAPDOOR, 10],
			6 => [BlockIds::IRON_TRAPDOOR, 9],
			7 => [BlockIds::IRON_TRAPDOOR, 8],
			8 => [BlockIds::IRON_TRAPDOOR, 7],
			9 => [BlockIds::IRON_TRAPDOOR, 6],
			10 => [BlockIds::IRON_TRAPDOOR, 5],
			11 => [BlockIds::IRON_TRAPDOOR, 4],
			12 => [BlockIds::IRON_TRAPDOOR, 15],
			13 => [BlockIds::IRON_TRAPDOOR, 14],
			14 => [BlockIds::IRON_TRAPDOOR, 13],
			15 => [BlockIds::IRON_TRAPDOOR, 12]
		],
		BlockIds::REPEATING_COMMAND_BLOCK => [
			[BlockIds::FENCE, 1]
		],
		BlockIds::CHAIN_COMMAND_BLOCK => [
			[BlockIds::FENCE, 2]
		],
		190 => [
			[BlockIds::FENCE, 3]
		],
		191 => [
			[BlockIds::FENCE, 5]
		],
		192 => [
			[BlockIds::FENCE, 4]
		],
		BlockIds::END_ROD => [
			[BlockIds::GRASS_PATH, 0]
		],
		BlockIds::BLACK_GLAZED_TERRACOTTA => [
			[BlockIds::WHITE_GLAZED_TERRACOTTA, 0]
		],
		BlockIds::CONCRETE => [
			[BlockIds::ORANGE_GLAZED_TERRACOTTA, 0]
		],
		BlockIds::CONCRETE_POWDER => [
			[BlockIds::MAGENTA_GLAZED_TERRACOTTA, 0]
		],
		238 => [
			[BlockIds::LIGHT_BLUE_GLAZED_TERRACOTTA, 0]
		],
		239 => [
			[BlockIds::YELLOW_GLAZED_TERRACOTTA, 0]
		],
		BlockIds::CHORUS_PLANT => [
			[BlockIds::LIME_GLAZED_TERRACOTTA, 0]
		],
		BlockIds::STAINED_GLASS => [
			[BlockIds::PINK_GLAZED_TERRACOTTA, 0]
		],
		242 => [
			[BlockIds::GRAY_GLAZED_TERRACOTTA, 0]
		],
		BlockIds::PODZOL => [ //Should be Light Gray Glazed Terracotta but does not exist.
			[0, 0]
		],
		BlockIds::BEETROOT_BLOCK => [
			[BlockIds::CYAN_GLAZED_TERRACOTTA, 0]
		],
		BlockIds::STONECUTTER => [
			[BlockIds::PURPLE_GLAZED_TERRACOTTA, 0]
		],
		BlockIds::GLOWING_OBSIDIAN => [
			[BlockIds::BLUE_GLAZED_TERRACOTTA, 0]
		],
		BlockIds::NETHER_REACTOR => [
			[BlockIds::BROWN_GLAZED_TERRACOTTA, 0]
		],
		BlockIds::INFO_UPDATE => [
			[BlockIds::GREEN_GLAZED_TERRACOTTA, 0]
		],
		BlockIds::INFO_UPDATE2 => [
			[BlockIds::RED_GLAZED_TERRACOTTA, 0]
		],
		BlockIds::MOVING_BLOCK => [
			[BlockIds::BLACK_GLAZED_TERRACOTTA, 0]
		]
		/*166 => [
			[416, 0] //MC-PE Barrier
		]*/
	];

	public static function load() : void{
		$tempArr = [];
		for($i = 1, $j = 5; $i <= 5; $i++, $j--){
			$tempArr[$i] = [BlockIds::STONE_BUTTON, $j];
		}
		self::$MAP[BlockIds::STONE_BUTTON] = $tempArr;

		$tempArr = [];
		for($i = 1, $j = 5; $i <= 5; $i++, $j--){
			$tempArr[$i] = [BlockIds::WOODEN_BUTTON, $j];
		}
		self::$MAP[BlockIds::WOODEN_BUTTON] = $tempArr;

		$tempArr = [];
		for($i = 0; $i <= 15; $i++){
			$tempArr[$i] = [BlockIds::STAINED_GLASS, $i];
		}
		self::$MAP[BlockIds::INVISIBLE_BEDROCK] = $tempArr;

		$tempArr = [];
		for($i = 0; $i <= 5; $i++){
			$tempArr[$i] = [BlockIds::DOUBLE_WOODEN_SLAB, $i];
		}
		self::$MAP[BlockIds::DROPPER] = $tempArr;

		$tempArr = [];
		for($i = 0; $i <= 13; $i++){
			$tempArr[$i] = [BlockIds::WOODEN_SLAB, $i];
		}
		self::$MAP[BlockIds::ACTIVATOR_RAIL] = $tempArr;

		$tempArr = [];
		for($i = 0; $i <= 15; $i++){
			$tempArr[$i] = [BlockIds::CONCRETE, $i];
		}
		self::$MAP[BlockIds::OBSERVER] = $tempArr;

		$tempArr = [];
		for($i = 0; $i <= 15; $i++){
			$tempArr[$i] = [BlockIds::CONCRETE_POWDER, $i];
		}
		self::$MAP[BlockIds::STRUCTURE_BLOCK] = $tempArr;

		$tempArr = [];
		for($i = 0; $i <= 15; $i++){
			$tempArr[$i] = [BlockIds::END_ROD, $i];
		}
		self::$MAP[BlockIds::GRASS_PATH] = $tempArr;
	}

	/**
	 * @return array
	 */
	public static function get() : array{
		return self::$MAP;
	}
}