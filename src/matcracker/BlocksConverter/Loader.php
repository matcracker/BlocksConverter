<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\level\Level;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Loader extends PluginBase
{
    /**@var Config $blockConfig */
    private $blockConfig;

    public function onEnable()
    {
        if (!file_exists($this->getDataFolder())) {
            @mkdir($this->getDataFolder());
        }

        if (!file_exists($this->getDataFolder() . "/backups")) {
            @mkdir($this->getDataFolder() . "/backups");
        }

        $this->blockConfig = new Config($this->getDataFolder() . "blocks.yml", Config::YAML, [
            "settings" => [
                "chunk-radius" => 10
            ],
            "blocks" => []
        ]);
        $this->blockConfig->save();

        $this->getLogger()->info(Utils::translateColors("§a" . $this->getDescription()->getName() . " v" . $this->getDescription()->getVersion() . " enabled!"));
    }

    public function onDisable()
    {
        $this->getLogger()->info(Utils::translateColors("§c" . $this->getDescription()->getName() . " successfully disabled"));
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        $name = strtolower($command->getName());
        if ($name === "convertqueue") {
            if ($sender->hasPermission("blocksconverter.commands.convertqueue")) {
                if (count($args) < 1 || count($args) > 2) {
                    $sender->sendMessage($command->getUsage());
                    return true;
                }
                $action = strtolower($args[0]);
                $levelOption = isset($args[1]) ? (string)$args[1] : "";
                /**@var string[] $levelNames */
                $levelNames = [];

                if (strtolower($levelOption) === "all") {
                    $levelNames = array_map(function (Level $level): string {
                        return $level->getName();
                    }, $this->getServer()->getLevels());
                } else {
                    $levelNames[] = $levelOption;
                }

                if ($action === "status") {
                    if (!LevelQueue::isEmpty()) {
                        $sender->sendMessage(Utils::translateColors("§6Levels in queue:"));
                        foreach (LevelQueue::getQueue() as $queue) {
                            $sender->sendMessage(Utils::translateColors("§b- " . $queue->getLevel()->getName()));
                        }
                    } else {
                        $sender->sendMessage(Utils::translateColors("§cAny conversion is in queue!"));
                    }
                } else {
                    foreach ($levelNames as $levelName) {
                        if ($action === "add") {
                            if (!LevelQueue::isInQueue($levelName)) {
                                if (($level = $this->getServer()->getLevelByName($levelName)) !== null) {
                                    LevelQueue::addInQueue(new LevelManager($this, $level));
                                    $sender->sendMessage(Utils::translateColors("§aLevel $levelName has been add in queue."));
                                } else {
                                    $sender->sendMessage(Utils::translateColors("§cLevel $levelName isn't loaded or does not exist."));
                                }
                            } else {
                                $sender->sendMessage(Utils::translateColors("§cLevel $levelName is already in queue!"));
                            }
                        } elseif ($action === "remove") {
                            if (LevelQueue::isInQueue($levelName)) {
                                LevelQueue::removeFromQueue($levelName);
                                $sender->sendMessage(Utils::translateColors("§aLevel $levelName removed from the queue."));
                            } else {
                                $sender->sendMessage(Utils::translateColors("§cLevel $levelName is not in queue."));
                            }
                        }
                    }
                }
            } else {
                $sender->sendMessage(Utils::translateColors("§cYou don't have permission to run this command!"));
            }
            return true;
        } elseif ($name === "convert") {
            if ($sender->hasPermission("blocksconverter.commands.convert")) {
                if (count($args) < 1 || count($args) > 2) {
                    $sender->sendMessage($command->getUsage());
                    return true;
                }

                $levelOpt = (string)$args[0];
                $backup = isset($args[1]) ? (bool)filter_var($args[1], FILTER_VALIDATE_BOOLEAN) : true;

                if (strtolower($levelOpt) === "queue") {
                    if (!LevelQueue::isEmpty()) {
                        $sender->sendMessage(Utils::translateColors("§3This process could takes a lot of time, so don't join or quit the game and wait patentitly the finish!"));
                        foreach (LevelQueue::getQueue() as $queue) {
                            $levelName = $queue->getLevel()->getName();
                            if ($backup) {
                                $sender->sendMessage(Utils::translateColors("§6Creating a backup of $levelName"));
                                $queue->backup();
                                $sender->sendMessage(Utils::translateColors("§aBackup createad successfully!"));
                            } else {
                                $sender->sendMessage(Utils::translateColors("§eNo backup will be created for the level $levelName"));
                            }
                            $sender->sendMessage(Utils::translateColors("§bStarting the $levelOpt's conversion..."));
                            $queue->startConversion();
                            $sender->sendMessage(Utils::translateColors("§3Check the console for the full report of conversion!"));
                        }
                    } else {
                        $sender->sendMessage(Utils::translateColors("§cAny conversion is in queue!"));
                    }
                } else {
                    $level = $this->getServer()->getLevelByName($levelOpt);
                    if ($level !== null) {
                        $sender->sendMessage(Utils::translateColors("§3This process could takes a some time, so don't join or quit the game and wait patentitly the finish!"));
                        $manager = new LevelManager($this, $level);
                        if ($backup) {
                            $sender->sendMessage(Utils::translateColors("§6Creating a backup of $levelOpt"));
                            $manager->backup();
                            $sender->sendMessage(Utils::translateColors("§aBackup createad successfully!"));
                        } else {
                            $sender->sendMessage(Utils::translateColors("§eNo backup will be created for the level $levelOpt"));
                        }
                        $sender->sendMessage(Utils::translateColors("§bStarting the $levelOpt's conversion..."));
                        $manager->startConversion();
                        $sender->sendMessage(Utils::translateColors("§3Check the console for the full report of conversion!"));
                    } else {
                        $sender->sendMessage(Utils::translateColors("§cLevel " . $levelOpt . " does not exist or isn't loaded!"));
                    }
                }
            } else {
                $sender->sendMessage(Utils::translateColors("§cYou don't have permission to run this command!"));
            }
            return true;
        }
        return false;
    }

    public function getBlocksConfig(): Config
    {
        return $this->blockConfig;
    }

    public function getBlocksData()
    {
        return $this->blockConfig->get("blocks");
    }

    public function getChunkRadius(): int
    {
        return (int)$this->blockConfig->getNested("settings.chunk-radius");
    }
}