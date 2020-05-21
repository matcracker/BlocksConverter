<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter\tasks;

use matcracker\BlocksConverter\commands\ToolBlock;
use pocketmine\block\BlockIds;
use pocketmine\scheduler\Task;

final class ToolBlockTask extends Task{
	public function onRun(int $currentTick){
		$players = ToolBlock::getPlayers();
		foreach($players as $player){
			$block = $player->getTargetBlock(5);
			if($block !== null && $block->getId() !== BlockIds::AIR){
				$message = "{$block->getName()} (ID: {$block->getId()} Meta: {$block->getDamage()})\n";
				$message .= "X: {$block->getX()} Y: {$block->getY()} Z: {$block->getZ()}";
				$player->sendTip($message);
			}
		}
	}
}