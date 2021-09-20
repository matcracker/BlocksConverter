<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter;

use matcracker\BlocksConverter\commands\Convert;
use matcracker\BlocksConverter\commands\ConvertQueue;
use matcracker\BlocksConverter\commands\ToolBlock;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\plugin\PluginBase;

final class Main extends PluginBase implements Listener{

	public function onLoad() : void{
		@mkdir($this->getDataFolder() . "/backups", 0777, true);
	}

	public function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getServer()->getCommandMap()->registerAll('blocksconverter', [
			new Convert($this),
			new ConvertQueue($this),
			new ToolBlock($this)
		]);
	}

	public function onPlayerQuit(PlayerQuitEvent $event) : void{
		ToolBlock::removePlayer($event->getPlayer());
	}
}
