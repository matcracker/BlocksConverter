<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter;

use pocketmine\block\BlockIds;

final class BlocksMap{

	/** @var int[][][] */
	private static $MAP = [
		BlockIds::DIRT => [
			2 => [BlockIds::PODZOL, 0]
		],
		BlockIds::STICKY_PISTON => [
			2 => [BlockIds::STICKY_PISTON, 3],
			3 => [BlockIds::STICKY_PISTON, 2],
			4 => [BlockIds::STICKY_PISTON, 5],
			5 => [BlockIds::STICKY_PISTON, 4]
		],
		BlockIds::TALL_GRASS => [
			0 => [BlockIds::DEAD_BUSH, 0]
		],
		BlockIds::PISTON => [
			3 => [BlockIds::PISTON, 2],
			4 => [BlockIds::PISTON, 5],
			5 => [BlockIds::PISTON, 4]
		],
		BlockIds::PISTON_ARM_COLLISION => [
			2 => [BlockIds::PISTON_ARM_COLLISION, 3],
			3 => [BlockIds::PISTON_ARM_COLLISION, 2],
			4 => [BlockIds::PISTON_ARM_COLLISION, 5],
			5 => [BlockIds::PISTON_ARM_COLLISION, 4]
		],
		BlockIds::DOUBLE_STONE_SLAB => [
			6 => [BlockIds::DOUBLE_STONE_SLAB, 7],
			7 => [BlockIds::DOUBLE_STONE_SLAB, 6],
			14 => [BlockIds::DOUBLE_STONE_SLAB, 15],
			15 => [BlockIds::DOUBLE_STONE_SLAB, 14]
		],
		BlockIds::STONE_SLAB => [
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
		BlockIds::UNPOWERED_COMPARATOR => [
			1 => [BlockIds::UNPOWERED_COMPARATOR, 6],
			4 => [BlockIds::UNPOWERED_COMPARATOR, 10]
		],
		BlockIds::QUARTZ_BLOCK => [
			3 => [BlockIds::QUARTZ_BLOCK, 6],
			4 => [BlockIds::QUARTZ_BLOCK, 10]
		],
		/*166 => [
			[416, 0] //MC-PE Barrier
		]*/
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
			0 => [BlockIds::FENCE, 1]
		],
		BlockIds::CHAIN_COMMAND_BLOCK => [
			0 => [BlockIds::FENCE, 2]
		],
		190 => [
			0 => [BlockIds::FENCE, 3]
		],
		191 => [
			0 => [BlockIds::FENCE, 5]
		],
		192 => [
			0 => [BlockIds::FENCE, 4]
		],
		202 => [
			0 => [BlockIds::PURPUR_BLOCK, 2],
			4 => [BlockIds::PURPUR_BLOCK, 6],
			8 => [BlockIds::PURPUR_BLOCK, 10]
		],
		204 => [
			0 => [BlockIds::DOUBLE_STONE_SLAB2, 1]
		],
		BlockIds::UNDYED_SHULKER_BOX => [
			0 => [BlockIds::STONE_SLAB2, 1],
			8 => [BlockIds::STONE_SLAB2, 9]
		],
		BlockIds::END_ROD => [
			[BlockIds::GRASS_PATH, 0]
		],
		212 => [
			[BlockIds::FROSTED_ICE, 0]
		],
		BlockIds::BLACK_GLAZED_TERRACOTTA => [
			0 => [BlockIds::WHITE_GLAZED_TERRACOTTA, 3],
			1 => [BlockIds::WHITE_GLAZED_TERRACOTTA, 4],
			2 => [BlockIds::WHITE_GLAZED_TERRACOTTA, 2],
			3 => [BlockIds::WHITE_GLAZED_TERRACOTTA, 5]
		],
		BlockIds::CONCRETE => [
			0 => [BlockIds::ORANGE_GLAZED_TERRACOTTA, 3],
			1 => [BlockIds::ORANGE_GLAZED_TERRACOTTA, 4],
			2 => [BlockIds::ORANGE_GLAZED_TERRACOTTA, 2],
			3 => [BlockIds::ORANGE_GLAZED_TERRACOTTA, 5]
		],
		BlockIds::CONCRETE_POWDER => [
			0 => [BlockIds::MAGENTA_GLAZED_TERRACOTTA, 3],
			1 => [BlockIds::MAGENTA_GLAZED_TERRACOTTA, 4],
			2 => [BlockIds::MAGENTA_GLAZED_TERRACOTTA, 2],
			3 => [BlockIds::MAGENTA_GLAZED_TERRACOTTA, 5]
		],
		238 => [
			0 => [BlockIds::LIGHT_BLUE_GLAZED_TERRACOTTA, 3],
			1 => [BlockIds::LIGHT_BLUE_GLAZED_TERRACOTTA, 4],
			2 => [BlockIds::LIGHT_BLUE_GLAZED_TERRACOTTA, 2],
			3 => [BlockIds::LIGHT_BLUE_GLAZED_TERRACOTTA, 5]
		],
		239 => [
			0 => [BlockIds::YELLOW_GLAZED_TERRACOTTA, 3],
			1 => [BlockIds::YELLOW_GLAZED_TERRACOTTA, 4],
			2 => [BlockIds::YELLOW_GLAZED_TERRACOTTA, 2],
			3 => [BlockIds::YELLOW_GLAZED_TERRACOTTA, 5]
		],
		BlockIds::CHORUS_PLANT => [
			0 => [BlockIds::LIME_GLAZED_TERRACOTTA, 3],
			1 => [BlockIds::LIME_GLAZED_TERRACOTTA, 4],
			2 => [BlockIds::LIME_GLAZED_TERRACOTTA, 2],
			3 => [BlockIds::LIME_GLAZED_TERRACOTTA, 5]
		],
		BlockIds::STAINED_GLASS => [
			0 => [BlockIds::PINK_GLAZED_TERRACOTTA, 3],
			1 => [BlockIds::PINK_GLAZED_TERRACOTTA, 4],
			2 => [BlockIds::PINK_GLAZED_TERRACOTTA, 2],
			3 => [BlockIds::PINK_GLAZED_TERRACOTTA, 5]
		],
		242 => [
			0 => [BlockIds::GRAY_GLAZED_TERRACOTTA, 3],
			1 => [BlockIds::GRAY_GLAZED_TERRACOTTA, 4],
			2 => [BlockIds::GRAY_GLAZED_TERRACOTTA, 2],
			3 => [BlockIds::GRAY_GLAZED_TERRACOTTA, 5]
		],
		BlockIds::PODZOL => [
			0 => [BlockIds::SILVER_GLAZED_TERRACOTTA, 3],
			1 => [BlockIds::SILVER_GLAZED_TERRACOTTA, 4],
			2 => [BlockIds::SILVER_GLAZED_TERRACOTTA, 2],
			3 => [BlockIds::SILVER_GLAZED_TERRACOTTA, 5]
		],
		BlockIds::BEETROOT_BLOCK => [
			0 => [BlockIds::CYAN_GLAZED_TERRACOTTA, 3],
			1 => [BlockIds::CYAN_GLAZED_TERRACOTTA, 4],
			2 => [BlockIds::CYAN_GLAZED_TERRACOTTA, 2],
			3 => [BlockIds::CYAN_GLAZED_TERRACOTTA, 5]
		],
		BlockIds::STONECUTTER => [
			0 => [BlockIds::PURPLE_GLAZED_TERRACOTTA, 3],
			1 => [BlockIds::PURPLE_GLAZED_TERRACOTTA, 4],
			2 => [BlockIds::PURPLE_GLAZED_TERRACOTTA, 2],
			3 => [BlockIds::PURPLE_GLAZED_TERRACOTTA, 5]
		],
		BlockIds::GLOWING_OBSIDIAN => [
			0 => [BlockIds::BLUE_GLAZED_TERRACOTTA, 3],
			1 => [BlockIds::BLUE_GLAZED_TERRACOTTA, 4],
			2 => [BlockIds::BLUE_GLAZED_TERRACOTTA, 2],
			3 => [BlockIds::BLUE_GLAZED_TERRACOTTA, 5]
		],
		BlockIds::NETHER_REACTOR => [
			0 => [BlockIds::BROWN_GLAZED_TERRACOTTA, 3],
			1 => [BlockIds::BROWN_GLAZED_TERRACOTTA, 4],
			2 => [BlockIds::BROWN_GLAZED_TERRACOTTA, 2],
			3 => [BlockIds::BROWN_GLAZED_TERRACOTTA, 5]
		],
		BlockIds::INFO_UPDATE => [
			0 => [BlockIds::GREEN_GLAZED_TERRACOTTA, 3],
			1 => [BlockIds::GREEN_GLAZED_TERRACOTTA, 4],
			2 => [BlockIds::GREEN_GLAZED_TERRACOTTA, 2],
			3 => [BlockIds::GREEN_GLAZED_TERRACOTTA, 5]
		],
		BlockIds::INFO_UPDATE2 => [
			0 => [BlockIds::RED_GLAZED_TERRACOTTA, 3],
			1 => [BlockIds::RED_GLAZED_TERRACOTTA, 4],
			2 => [BlockIds::RED_GLAZED_TERRACOTTA, 2],
			3 => [BlockIds::RED_GLAZED_TERRACOTTA, 5]
		],
		BlockIds::MOVING_BLOCK => [
			0 => [BlockIds::BLACK_GLAZED_TERRACOTTA, 3],
			1 => [BlockIds::BLACK_GLAZED_TERRACOTTA, 4],
			2 => [BlockIds::BLACK_GLAZED_TERRACOTTA, 2],
			3 => [BlockIds::BLACK_GLAZED_TERRACOTTA, 5]
		],
		BlockIds::RESERVED6 => [
			[BlockIds::STRUCTURE_BLOCK, 0]
		]
	];

	public static function load() : void{
		$tempArr = [];
		for($i = 1, $j = 5; $i <= 5; $i++, $j--){
			$tempArr[$i] = [BlockIds::STONE_BUTTON, $j];
		}
		self::$MAP[BlockIds::STONE_BUTTON] = $tempArr;

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
		for($i = 0; $i <= 10; $i++){
			$tempArr[$i] = [BlockIds::WOODEN_SLAB, $i];
		}
		self::$MAP[BlockIds::ACTIVATOR_RAIL] = $tempArr;

		$tempArr = [];
		for($i = 1, $j = 5; $i <= 5; $i++, $j--){
			$tempArr[$i] = [BlockIds::WOODEN_BUTTON, $j];
		}
		self::$MAP[BlockIds::WOODEN_BUTTON] = $tempArr;

		$tempArr = [];
		for($i = 0; $i <= 15; $i++){
			$tempArr[$i] = [BlockIds::ACTIVATOR_RAIL, $i];
		}
		self::$MAP[BlockIds::WOODEN_SLAB] = $tempArr;

		$tempArr = [];
		for($i = 0; $i <= 5; $i++){
			$tempArr[$i] = [BlockIds::DROPPER, $i];
		}
		self::$MAP[BlockIds::WOODEN_SLAB] = $tempArr;

		$tempArr = [];
		for($i = 0; $i <= 15; $i++){
			$tempArr[$i] = [BlockIds::END_ROD, $i];
		}
		$tempArr[2] = [BlockIds::END_ROD, 3];
		$tempArr[3] = [BlockIds::END_ROD, 2];
		$tempArr[4] = [BlockIds::END_ROD, 5];
		$tempArr[5] = [BlockIds::END_ROD, 4];
		self::$MAP[BlockIds::GRASS_PATH] = $tempArr;

		$tempArr = [];
		for($i = 0; $i <= 15; $i++){
			$tempArr[$i] = [BlockIds::CHORUS_PLANT, $i];
		}
		self::$MAP[BlockIds::ITEM_FRAME_BLOCK] = $tempArr;

		$tempArr = [];
		for($i = 0; $i <= 7; $i++){
			$tempArr[$i] = [BlockIds::BEETROOT_BLOCK, $i];
		}
		self::$MAP[BlockIds::FROSTED_ICE] = $tempArr;

		$tempArr = [];
		for($i = 0; $i <= 15; $i++){
			$tempArr[$i] = [BlockIds::REPEATING_COMMAND_BLOCK, $i];
		}
		self::$MAP[210] = $tempArr;

		$tempArr = [];
		for($i = 0; $i <= 15; $i++){
			$tempArr[$i] = [BlockIds::CHAIN_COMMAND_BLOCK, $i];
		}
		self::$MAP[211] = $tempArr;

		$tempArr = [];
		for($i = 0; $i <= 15; $i++){
			$tempArr[$i] = [BlockIds::OBSERVER, $i];
		}
		self::$MAP[BlockIds::SHULKER_BOX] = $tempArr;

		$tempArr = [];
		//Glazed terracotta to shulker box
		for($i = BlockIds::PURPLE_GLAZED_TERRACOTTA; $i <= BlockIds::RED_GLAZED_TERRACOTTA; $i++){
			if($i === BlockIds::CYAN_GLAZED_TERRACOTTA){
				for($k = 0; $k <= 15; $k++){
					$tempArr[$k] = [BlockIds::UNDYED_SHULKER_BOX, $j];
				}
			}else{
				for($k = 0; $k <= 15; $k++){
					$tempArr[$k] = [BlockIds::SHULKER_BOX, $j];
				}
			}
			self::$MAP[$i] = $tempArr;
			$j++;
		}

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
	}

	/**
	 * @return int[][][]
	 */
	public static function reverse() : array{
		$newMap = [];
		foreach(self::$MAP as $javaId => $javaData){
			foreach($javaData as $javaMeta => $bedrockData){
				$bedrockId = (int) $bedrockData[0];
				$bedrockMeta = (int) $bedrockData[1];

				$newMap[$bedrockId][$bedrockMeta] = [$javaId, $javaMeta];
			}
		}

		return $newMap;
	}

	/**
	 * @return int[][][]
	 */
	public static function get() : array{
		return self::$MAP;
	}
}