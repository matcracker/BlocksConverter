<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter\tasks;

use matcracker\BlocksConverter\commands\ToolBlock;
use pocketmine\block\BlockIds;
use pocketmine\scheduler\Task;
use function count;

final class ToolBlockTask extends Task{

	public function onRun(int $currentTick) : void{
		$players = ToolBlock::getPlayers();

		if(count($players) === 0){
			$this->getHandler()->cancel();
			return;
		}

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