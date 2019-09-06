<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter;

use matcracker\BlocksConverter\commands\Convert;
use matcracker\BlocksConverter\commands\ConvertQueue;
use matcracker\BlocksConverter\commands\ToolBlock;
use pocketmine\block\BlockIds;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\Task;

final class Loader extends PluginBase implements Listener
{

	public function onLoad(): void
	{
		@mkdir($this->getDataFolder() . "/backups", 0777, true);
		BlocksMap::load();
	}

	public function onEnable(): void
	{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getServer()->getCommandMap()->register('convert', new Convert($this));
		$this->getServer()->getCommandMap()->register('convertqueue', new ConvertQueue($this));
		$this->getServer()->getCommandMap()->register('toolblock', new ToolBlock());

		$this->getScheduler()->scheduleRepeatingTask(new class extends Task
		{
			public function onRun(int $currentTick)
			{
				$players = ToolBlock::getPlayers();
				foreach ($players as $player) {
					$block = $player->getTargetBlock(5);
					if ($block !== null && $block->getId() !== BlockIds::AIR) {
						$message = "{$block->getName()} (ID: {$block->getId()} Meta: {$block->getDamage()})\n";
						$message .= "X: {$block->getX()} Y: {$block->getY()} Z: {$block->getZ()}";
						$player->sendTip($message);
					}
				}
			}
		}, 5);
	}

	public function onPlayerQuit(PlayerQuitEvent $event): void
	{
		ToolBlock::removePlayer($event->getPlayer());
	}

	public function onDisable(): void
	{
		$this->getScheduler()->cancelAllTasks();
	}
}
