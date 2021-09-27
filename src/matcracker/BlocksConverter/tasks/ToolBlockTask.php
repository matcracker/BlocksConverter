<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter\tasks;

use matcracker\BlocksConverter\commands\ToolBlock;
use pocketmine\block\Air;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;
use function count;

final class ToolBlockTask extends Task{

	public function onRun() : void{
		$players = ToolBlock::getPlayers();

		if(count($players) === 0){
			$this->getHandler()?->cancel();

			return;
		}

		foreach($players as $player){
			$block = $player?->getTargetBlock(5);
			if($block !== null && !($block instanceof Air)){
				$pos = $block->getPosition();
				$message = "{$block->getName()} (ID: {$block->getId()} Meta: {$block->getMeta()})" . TextFormat::EOL;
				$message .= "X: {$pos->getX()} Y: {$pos->getY()} Z: {$pos->getZ()}";
				$player->sendTip($message);
			}
		}
	}
}