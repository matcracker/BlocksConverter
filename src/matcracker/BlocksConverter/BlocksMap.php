<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter;

use pocketmine\block\Block;
use pocketmine\block\BlockLegacyIds;
use function array_flip;

final class BlocksMap{

	/** @var int[] */
	private static array $map = [];

	public static function init() : void{
		$legacyMap = [
			BlockLegacyIds::DIRT => [
				2 => [BlockLegacyIds::PODZOL, 0]
			],
			BlockLegacyIds::STICKY_PISTON => [
				2 => [BlockLegacyIds::STICKY_PISTON, 3],
				3 => [BlockLegacyIds::STICKY_PISTON, 2],
				4 => [BlockLegacyIds::STICKY_PISTON, 5],
				5 => [BlockLegacyIds::STICKY_PISTON, 4]
			],
			BlockLegacyIds::TALL_GRASS => [
				0 => [BlockLegacyIds::DEAD_BUSH, 0]
			],
			BlockLegacyIds::PISTON => [
				3 => [BlockLegacyIds::PISTON, 2],
				4 => [BlockLegacyIds::PISTON, 5],
				5 => [BlockLegacyIds::PISTON, 4]
			],
			BlockLegacyIds::PISTON_ARM_COLLISION => [
				2 => [BlockLegacyIds::PISTON_ARM_COLLISION, 3],
				3 => [BlockLegacyIds::PISTON_ARM_COLLISION, 2],
				4 => [BlockLegacyIds::PISTON_ARM_COLLISION, 5],
				5 => [BlockLegacyIds::PISTON_ARM_COLLISION, 4]
			],
			BlockLegacyIds::DOUBLE_STONE_SLAB => [
				6 => [BlockLegacyIds::DOUBLE_STONE_SLAB, 7],
				7 => [BlockLegacyIds::DOUBLE_STONE_SLAB, 6],
				14 => [BlockLegacyIds::DOUBLE_STONE_SLAB, 15],
				15 => [BlockLegacyIds::DOUBLE_STONE_SLAB, 14]
			],
			BlockLegacyIds::STONE_SLAB => [
				6 => [BlockLegacyIds::STONE_SLAB, 7],
				7 => [BlockLegacyIds::STONE_SLAB, 6],
				14 => [BlockLegacyIds::STONE_SLAB, 15],
				15 => [BlockLegacyIds::STONE_SLAB, 14]
			],
			BlockLegacyIds::TRAPDOOR => [
				0 => [BlockLegacyIds::TRAPDOOR, 3],
				1 => [BlockLegacyIds::TRAPDOOR, 2],
				2 => [BlockLegacyIds::TRAPDOOR, 1],
				3 => [BlockLegacyIds::TRAPDOOR, 0],
				4 => [BlockLegacyIds::TRAPDOOR, 11],
				5 => [BlockLegacyIds::TRAPDOOR, 10],
				6 => [BlockLegacyIds::TRAPDOOR, 9],
				7 => [BlockLegacyIds::TRAPDOOR, 8],
				8 => [BlockLegacyIds::TRAPDOOR, 7],
				9 => [BlockLegacyIds::TRAPDOOR, 6],
				10 => [BlockLegacyIds::TRAPDOOR, 5],
				11 => [BlockLegacyIds::TRAPDOOR, 4],
				12 => [BlockLegacyIds::TRAPDOOR, 15],
				13 => [BlockLegacyIds::TRAPDOOR, 14],
				14 => [BlockLegacyIds::TRAPDOOR, 13],
				15 => [BlockLegacyIds::TRAPDOOR, 12]
			],
			BlockLegacyIds::UNPOWERED_COMPARATOR => [
				1 => [BlockLegacyIds::UNPOWERED_COMPARATOR, 6],
				4 => [BlockLegacyIds::UNPOWERED_COMPARATOR, 10]
			],
			BlockLegacyIds::QUARTZ_BLOCK => [
				3 => [BlockLegacyIds::QUARTZ_BLOCK, 6],
				4 => [BlockLegacyIds::QUARTZ_BLOCK, 10]
			],
			/*166 => [
				[416, 0] //MC-PE Barrier
			]*/
			BlockLegacyIds::IRON_TRAPDOOR => [
				0 => [BlockLegacyIds::IRON_TRAPDOOR, 3],
				1 => [BlockLegacyIds::IRON_TRAPDOOR, 2],
				2 => [BlockLegacyIds::IRON_TRAPDOOR, 1],
				3 => [BlockLegacyIds::IRON_TRAPDOOR, 0],
				4 => [BlockLegacyIds::IRON_TRAPDOOR, 11],
				5 => [BlockLegacyIds::IRON_TRAPDOOR, 10],
				6 => [BlockLegacyIds::IRON_TRAPDOOR, 9],
				7 => [BlockLegacyIds::IRON_TRAPDOOR, 8],
				8 => [BlockLegacyIds::IRON_TRAPDOOR, 7],
				9 => [BlockLegacyIds::IRON_TRAPDOOR, 6],
				10 => [BlockLegacyIds::IRON_TRAPDOOR, 5],
				11 => [BlockLegacyIds::IRON_TRAPDOOR, 4],
				12 => [BlockLegacyIds::IRON_TRAPDOOR, 15],
				13 => [BlockLegacyIds::IRON_TRAPDOOR, 14],
				14 => [BlockLegacyIds::IRON_TRAPDOOR, 13],
				15 => [BlockLegacyIds::IRON_TRAPDOOR, 12]
			],
			BlockLegacyIds::REPEATING_COMMAND_BLOCK => [
				0 => [BlockLegacyIds::FENCE, 1]
			],
			BlockLegacyIds::CHAIN_COMMAND_BLOCK => [
				0 => [BlockLegacyIds::FENCE, 2]
			],
			190 => [
				0 => [BlockLegacyIds::FENCE, 3]
			],
			191 => [
				0 => [BlockLegacyIds::FENCE, 5]
			],
			192 => [
				0 => [BlockLegacyIds::FENCE, 4]
			],
			202 => [
				0 => [BlockLegacyIds::PURPUR_BLOCK, 2],
				4 => [BlockLegacyIds::PURPUR_BLOCK, 6],
				8 => [BlockLegacyIds::PURPUR_BLOCK, 10]
			],
			204 => [
				0 => [BlockLegacyIds::DOUBLE_STONE_SLAB2, 1]
			],
			BlockLegacyIds::UNDYED_SHULKER_BOX => [
				0 => [BlockLegacyIds::STONE_SLAB2, 1],
				8 => [BlockLegacyIds::STONE_SLAB2, 9]
			],
			BlockLegacyIds::END_ROD => [
				[BlockLegacyIds::GRASS_PATH, 0]
			],
			212 => [
				[BlockLegacyIds::FROSTED_ICE, 0]
			],
			BlockLegacyIds::BLACK_GLAZED_TERRACOTTA => [
				0 => [BlockLegacyIds::WHITE_GLAZED_TERRACOTTA, 3],
				1 => [BlockLegacyIds::WHITE_GLAZED_TERRACOTTA, 4],
				2 => [BlockLegacyIds::WHITE_GLAZED_TERRACOTTA, 2],
				3 => [BlockLegacyIds::WHITE_GLAZED_TERRACOTTA, 5]
			],
			BlockLegacyIds::CONCRETE => [
				0 => [BlockLegacyIds::ORANGE_GLAZED_TERRACOTTA, 3],
				1 => [BlockLegacyIds::ORANGE_GLAZED_TERRACOTTA, 4],
				2 => [BlockLegacyIds::ORANGE_GLAZED_TERRACOTTA, 2],
				3 => [BlockLegacyIds::ORANGE_GLAZED_TERRACOTTA, 5]
			],
			BlockLegacyIds::CONCRETE_POWDER => [
				0 => [BlockLegacyIds::MAGENTA_GLAZED_TERRACOTTA, 3],
				1 => [BlockLegacyIds::MAGENTA_GLAZED_TERRACOTTA, 4],
				2 => [BlockLegacyIds::MAGENTA_GLAZED_TERRACOTTA, 2],
				3 => [BlockLegacyIds::MAGENTA_GLAZED_TERRACOTTA, 5]
			],
			238 => [
				0 => [BlockLegacyIds::LIGHT_BLUE_GLAZED_TERRACOTTA, 3],
				1 => [BlockLegacyIds::LIGHT_BLUE_GLAZED_TERRACOTTA, 4],
				2 => [BlockLegacyIds::LIGHT_BLUE_GLAZED_TERRACOTTA, 2],
				3 => [BlockLegacyIds::LIGHT_BLUE_GLAZED_TERRACOTTA, 5]
			],
			239 => [
				0 => [BlockLegacyIds::YELLOW_GLAZED_TERRACOTTA, 3],
				1 => [BlockLegacyIds::YELLOW_GLAZED_TERRACOTTA, 4],
				2 => [BlockLegacyIds::YELLOW_GLAZED_TERRACOTTA, 2],
				3 => [BlockLegacyIds::YELLOW_GLAZED_TERRACOTTA, 5]
			],
			BlockLegacyIds::CHORUS_PLANT => [
				0 => [BlockLegacyIds::LIME_GLAZED_TERRACOTTA, 3],
				1 => [BlockLegacyIds::LIME_GLAZED_TERRACOTTA, 4],
				2 => [BlockLegacyIds::LIME_GLAZED_TERRACOTTA, 2],
				3 => [BlockLegacyIds::LIME_GLAZED_TERRACOTTA, 5]
			],
			BlockLegacyIds::STAINED_GLASS => [
				0 => [BlockLegacyIds::PINK_GLAZED_TERRACOTTA, 3],
				1 => [BlockLegacyIds::PINK_GLAZED_TERRACOTTA, 4],
				2 => [BlockLegacyIds::PINK_GLAZED_TERRACOTTA, 2],
				3 => [BlockLegacyIds::PINK_GLAZED_TERRACOTTA, 5]
			],
			242 => [
				0 => [BlockLegacyIds::GRAY_GLAZED_TERRACOTTA, 3],
				1 => [BlockLegacyIds::GRAY_GLAZED_TERRACOTTA, 4],
				2 => [BlockLegacyIds::GRAY_GLAZED_TERRACOTTA, 2],
				3 => [BlockLegacyIds::GRAY_GLAZED_TERRACOTTA, 5]
			],
			BlockLegacyIds::PODZOL => [
				0 => [BlockLegacyIds::SILVER_GLAZED_TERRACOTTA, 3],
				1 => [BlockLegacyIds::SILVER_GLAZED_TERRACOTTA, 4],
				2 => [BlockLegacyIds::SILVER_GLAZED_TERRACOTTA, 2],
				3 => [BlockLegacyIds::SILVER_GLAZED_TERRACOTTA, 5]
			],
			BlockLegacyIds::BEETROOT_BLOCK => [
				0 => [BlockLegacyIds::CYAN_GLAZED_TERRACOTTA, 3],
				1 => [BlockLegacyIds::CYAN_GLAZED_TERRACOTTA, 4],
				2 => [BlockLegacyIds::CYAN_GLAZED_TERRACOTTA, 2],
				3 => [BlockLegacyIds::CYAN_GLAZED_TERRACOTTA, 5]
			],
			BlockLegacyIds::STONECUTTER => [
				0 => [BlockLegacyIds::PURPLE_GLAZED_TERRACOTTA, 3],
				1 => [BlockLegacyIds::PURPLE_GLAZED_TERRACOTTA, 4],
				2 => [BlockLegacyIds::PURPLE_GLAZED_TERRACOTTA, 2],
				3 => [BlockLegacyIds::PURPLE_GLAZED_TERRACOTTA, 5]
			],
			BlockLegacyIds::GLOWING_OBSIDIAN => [
				0 => [BlockLegacyIds::BLUE_GLAZED_TERRACOTTA, 3],
				1 => [BlockLegacyIds::BLUE_GLAZED_TERRACOTTA, 4],
				2 => [BlockLegacyIds::BLUE_GLAZED_TERRACOTTA, 2],
				3 => [BlockLegacyIds::BLUE_GLAZED_TERRACOTTA, 5]
			],
			BlockLegacyIds::NETHER_REACTOR => [
				0 => [BlockLegacyIds::BROWN_GLAZED_TERRACOTTA, 3],
				1 => [BlockLegacyIds::BROWN_GLAZED_TERRACOTTA, 4],
				2 => [BlockLegacyIds::BROWN_GLAZED_TERRACOTTA, 2],
				3 => [BlockLegacyIds::BROWN_GLAZED_TERRACOTTA, 5]
			],
			BlockLegacyIds::INFO_UPDATE => [
				0 => [BlockLegacyIds::GREEN_GLAZED_TERRACOTTA, 3],
				1 => [BlockLegacyIds::GREEN_GLAZED_TERRACOTTA, 4],
				2 => [BlockLegacyIds::GREEN_GLAZED_TERRACOTTA, 2],
				3 => [BlockLegacyIds::GREEN_GLAZED_TERRACOTTA, 5]
			],
			BlockLegacyIds::INFO_UPDATE2 => [
				0 => [BlockLegacyIds::RED_GLAZED_TERRACOTTA, 3],
				1 => [BlockLegacyIds::RED_GLAZED_TERRACOTTA, 4],
				2 => [BlockLegacyIds::RED_GLAZED_TERRACOTTA, 2],
				3 => [BlockLegacyIds::RED_GLAZED_TERRACOTTA, 5]
			],
			BlockLegacyIds::MOVING_BLOCK => [
				0 => [BlockLegacyIds::BLACK_GLAZED_TERRACOTTA, 3],
				1 => [BlockLegacyIds::BLACK_GLAZED_TERRACOTTA, 4],
				2 => [BlockLegacyIds::BLACK_GLAZED_TERRACOTTA, 2],
				3 => [BlockLegacyIds::BLACK_GLAZED_TERRACOTTA, 5]
			],
			BlockLegacyIds::RESERVED6 => [
				[BlockLegacyIds::STRUCTURE_BLOCK, 0]
			]
		];

		$tempArr = [];
		for($i = 1, $j = 5; $i <= 5; $i++, $j--){
			$tempArr[$i] = [BlockLegacyIds::STONE_BUTTON, $j];
		}
		$legacyMap[BlockLegacyIds::STONE_BUTTON] = $tempArr;

		$tempArr = [];
		for($i = 0; $i <= 15; $i++){
			$tempArr[$i] = [BlockLegacyIds::STAINED_GLASS, $i];
		}
		$legacyMap[BlockLegacyIds::INVISIBLE_BEDROCK] = $tempArr;

		$tempArr = [];
		for($i = 0; $i <= 5; $i++){
			$tempArr[$i] = [BlockLegacyIds::DOUBLE_WOODEN_SLAB, $i];
		}
		$legacyMap[BlockLegacyIds::DROPPER] = $tempArr;

		$tempArr = [];
		for($i = 0; $i <= 15; $i++){
			$tempArr[$i] = [BlockLegacyIds::WOODEN_SLAB, $i];
		}
		$legacyMap[BlockLegacyIds::ACTIVATOR_RAIL] = $tempArr;

		$tempArr = [];
		for($i = 1, $j = 5; $i <= 5; $i++, $j--){
			$tempArr[$i] = [BlockLegacyIds::WOODEN_BUTTON, $j];
		}
		$legacyMap[BlockLegacyIds::WOODEN_BUTTON] = $tempArr;

		$tempArr = [];
		for($i = 0; $i <= 9; $i++){
			$tempArr[$i] = [BlockLegacyIds::ACTIVATOR_RAIL, $i];
		}
		$legacyMap[BlockLegacyIds::DOUBLE_WOODEN_SLAB] = $tempArr;

		$tempArr = [];
		for($i = 0; $i <= 5; $i++){
			$tempArr[$i] = [BlockLegacyIds::DROPPER, $i];
		}
		$legacyMap[BlockLegacyIds::WOODEN_SLAB] = $tempArr;

		$tempArr = [];
		for($i = 0; $i <= 15; $i++){
			$tempArr[$i] = [BlockLegacyIds::END_ROD, $i];
		}
		$tempArr[2] = [BlockLegacyIds::END_ROD, 3];
		$tempArr[3] = [BlockLegacyIds::END_ROD, 2];
		$tempArr[4] = [BlockLegacyIds::END_ROD, 5];
		$tempArr[5] = [BlockLegacyIds::END_ROD, 4];
		$legacyMap[BlockLegacyIds::GRASS_PATH] = $tempArr;

		$tempArr = [];
		for($i = 0; $i <= 15; $i++){
			$tempArr[$i] = [BlockLegacyIds::CHORUS_PLANT, $i];
		}
		$legacyMap[BlockLegacyIds::ITEM_FRAME_BLOCK] = $tempArr;

		$tempArr = [];
		for($i = 0; $i <= 7; $i++){
			$tempArr[$i] = [BlockLegacyIds::BEETROOT_BLOCK, $i];
		}
		$legacyMap[BlockLegacyIds::FROSTED_ICE] = $tempArr;

		$tempArr = [];
		for($i = 0; $i <= 15; $i++){
			$tempArr[$i] = [BlockLegacyIds::REPEATING_COMMAND_BLOCK, $i];
		}
		$legacyMap[210] = $tempArr;

		$tempArr = [];
		for($i = 0; $i <= 15; $i++){
			$tempArr[$i] = [BlockLegacyIds::CHAIN_COMMAND_BLOCK, $i];
		}
		$legacyMap[211] = $tempArr;

		$tempArr = [];
		for($i = 0; $i <= 15; $i++){
			$tempArr[$i] = [BlockLegacyIds::OBSERVER, $i];
		}
		$legacyMap[BlockLegacyIds::SHULKER_BOX] = $tempArr;

		$tempArr = [];
		//Glazed terracotta to shulker box
		for($i = BlockLegacyIds::PURPLE_GLAZED_TERRACOTTA; $i <= BlockLegacyIds::RED_GLAZED_TERRACOTTA; $i++){
			if($i === BlockLegacyIds::CYAN_GLAZED_TERRACOTTA){
				for($k = 0; $k <= 15; $k++){
					$tempArr[$k] = [BlockLegacyIds::UNDYED_SHULKER_BOX, $j];
				}
			}else{
				for($k = 0; $k <= 15; $k++){
					$tempArr[$k] = [BlockLegacyIds::SHULKER_BOX, $j];
				}
			}
			$legacyMap[$i] = $tempArr;
			$j++;
		}

		$tempArr = [];
		for($i = 0; $i <= 15; $i++){
			$tempArr[$i] = [BlockLegacyIds::CONCRETE, $i];
		}
		$legacyMap[BlockLegacyIds::OBSERVER] = $tempArr;

		$tempArr = [];
		for($i = 0; $i <= 15; $i++){
			$tempArr[$i] = [BlockLegacyIds::CONCRETE_POWDER, $i];
		}
		$legacyMap[BlockLegacyIds::STRUCTURE_BLOCK] = $tempArr;

		foreach($legacyMap as $javaId => $javaData){
			foreach($javaData as $javaMeta => $bedrockData){
				$bedrockId = (int) $bedrockData[0];
				$bedrockMeta = (int) $bedrockData[1];

				self::$map[self::toFullBlockId($bedrockId, $bedrockMeta)] = self::toFullBlockId($javaId, $javaMeta);
			}
		}
	}

	private static function toFullBlockId(int $blockId, int $blockMeta) : int{
		return ($blockId << Block::INTERNAL_METADATA_BITS) | $blockMeta;
	}

	public static function getBedrockMap() : array{
		return array_flip(self::$map);
	}

	/**
	 * @return int[]
	 */
	public static function getJavaMap() : array{
		return self::$map;
	}
}