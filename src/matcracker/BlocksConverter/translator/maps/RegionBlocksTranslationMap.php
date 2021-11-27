<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter\translator\maps;

use pocketmine\block\BlockLegacyIds;

final class RegionBlocksTranslationMap extends BlocksTranslationMap{

	public function __construct(){
		$this->map = [
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

		for($i = 1, $j = 5; $i <= 5; $i++, $j--){
			$this->map[BlockLegacyIds::STONE_BUTTON][$i] = [BlockLegacyIds::STONE_BUTTON, $j];
		}

		for($i = 0; $i <= 15; $i++){
			$this->map[BlockLegacyIds::INVISIBLE_BEDROCK][$i] = [BlockLegacyIds::STAINED_GLASS, $i];
		}

		for($i = 0; $i <= 5; $i++){
			$this->map[BlockLegacyIds::DROPPER][$i] = [BlockLegacyIds::DOUBLE_WOODEN_SLAB, $i];
		}

		for($i = 0; $i <= 15; $i++){
			$this->map[BlockLegacyIds::ACTIVATOR_RAIL][$i] = [BlockLegacyIds::WOODEN_SLAB, $i];
		}

		for($i = 1, $j = 5; $i <= 5; $i++, $j--){
			$this->map[BlockLegacyIds::WOODEN_BUTTON][$i] = [BlockLegacyIds::WOODEN_BUTTON, $j];
		}

		for($i = 0; $i <= 9; $i++){
			$this->map[BlockLegacyIds::DOUBLE_WOODEN_SLAB][$i] = [BlockLegacyIds::ACTIVATOR_RAIL, $i];
		}

		for($i = 0; $i <= 5; $i++){
			$this->map[BlockLegacyIds::WOODEN_SLAB][$i] = [BlockLegacyIds::DROPPER, $i];
		}

		for($i = 0; $i <= 15; $i++){
			$this->map[BlockLegacyIds::GRASS_PATH][$i] = [BlockLegacyIds::END_ROD, $i];
		}
		$this->map[BlockLegacyIds::GRASS_PATH][2] = [BlockLegacyIds::END_ROD, 3];
		$this->map[BlockLegacyIds::GRASS_PATH][3] = [BlockLegacyIds::END_ROD, 2];
		$this->map[BlockLegacyIds::GRASS_PATH][4] = [BlockLegacyIds::END_ROD, 5];
		$this->map[BlockLegacyIds::GRASS_PATH][5] = [BlockLegacyIds::END_ROD, 4];

		for($i = 0; $i <= 15; $i++){
			$this->map[BlockLegacyIds::ITEM_FRAME_BLOCK][$i] = [BlockLegacyIds::CHORUS_PLANT, $i];
		}

		for($i = 0; $i <= 7; $i++){
			$this->map[BlockLegacyIds::FROSTED_ICE][$i] = [BlockLegacyIds::BEETROOT_BLOCK, $i];
		}

		for($i = 0; $i <= 15; $i++){
			$this->map[210][$i] = [BlockLegacyIds::REPEATING_COMMAND_BLOCK, $i];
		}

		for($i = 0; $i <= 15; $i++){
			$this->map[211][$i] = [BlockLegacyIds::CHAIN_COMMAND_BLOCK, $i];
		}

		for($i = 0; $i <= 15; $i++){
			$this->map[BlockLegacyIds::SHULKER_BOX][$i] = [BlockLegacyIds::OBSERVER, $i];
		}

		//Glazed terracotta to shulker box
		for($i = BlockLegacyIds::PURPLE_GLAZED_TERRACOTTA; $i <= BlockLegacyIds::RED_GLAZED_TERRACOTTA; $i++){
			if($i === BlockLegacyIds::CYAN_GLAZED_TERRACOTTA){
				for($k = 0; $k <= 15; $k++){
					$this->map[$i][$k] = [BlockLegacyIds::UNDYED_SHULKER_BOX, $j];
				}
			}else{
				for($k = 0; $k <= 15; $k++){
					$this->map[$i][$k] = [BlockLegacyIds::SHULKER_BOX, $j];
				}
			}
			$j++;
		}

		for($i = 0; $i <= 15; $i++){
			$this->map[BlockLegacyIds::OBSERVER][$i] = [BlockLegacyIds::CONCRETE, $i];
		}

		for($i = 0; $i <= 15; $i++){
			$this->map[BlockLegacyIds::STRUCTURE_BLOCK][$i] = [BlockLegacyIds::CONCRETE_POWDER, $i];
		}
	}
}