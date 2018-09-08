<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter;

use pocketmine\block\Block;
use pocketmine\level\format\EmptySubChunk;
use pocketmine\level\Level;
use pocketmine\tile\Sign;
use pocketmine\utils\TextFormat;

class LevelManager
{
    const IGNORE_DATA_VALUE = 99;

    /**@var Loader $loader */
    private $loader;
    /**@var Level $level */
    private $level;
    /**@var bool $converting */
    private $converting = false;

    public function __construct(Loader $loader, Level $level)
    {
        $this->loader = $loader;
        $this->level = $level;
    }

    public function getLevel(): Level
    {
        return $this->level;
    }

    public function backup(): void
    {
        $this->loader->getLogger()->debug(Utils::translateColors("§6Creating a backup of " . $this->level->getName()));
        $srcPath = $this->loader->getServer()->getDataPath() . "/worlds/" . $this->level->getFolderName();
        $destPath = $this->loader->getDataFolder() . "/backups/" . $this->level->getFolderName();
        Utils::copyDirectory($srcPath, $destPath);
        $this->loader->getLogger()->debug(Utils::translateColors("§aBackup successfully created!"));
    }

    public function restore(): void
    {
        $srcPath = $this->loader->getDataFolder() . "/backups/" . $this->level->getFolderName();
        if (!$this->hasBackup()) {
            throw new \InvalidStateException("This world never gets a backup.");
        }

        $destPath = $this->loader->getServer()->getDataPath() . "/worlds/" . $this->level->getFolderName();

        Utils::copyDirectory($srcPath, $destPath);
    }

    public function hasBackup(): bool
    {
        return file_exists($this->loader->getDataFolder() . "/backups/" . $this->level->getFolderName());
    }

    public function unloadLevel(): bool
    {
        return $this->loader->getServer()->unloadLevel($this->level);
    }

    public function isConverting(): bool
    {
        return $this->converting;
    }

    public function startConversion(): void
    {
        /**@var string[] $errors */
        $errors = $this->startAnalysis();

        if (!empty($errors)) {
            $this->loader->getLogger()->error("Found " . count($errors) . " error(s) before starting the conversion. List:");
            foreach ($errors as $error) {
                $this->loader->getLogger()->error("- " . $error);
            }
        } else {
            //Conversion report variables
            $status = true;
            $chunksAnalyzed = $subChunksAnalyzed = $convertedBlocks = $convertedSigns = 0;

            $time_start = microtime(true);

            if (!$this->hasBackup()) {
                $this->loader->getLogger()->warning("The level " . $this->level->getName() . " will be converted without a backup.");
            }

            $this->loader->getLogger()->debug(Utils::translateColors("§6Starting level " . $this->level->getName() . "'s conversion..."));
            foreach ($this->loader->getServer()->getOnlinePlayers() as $player) {
                $player->kick("The server is running a world conversion, try to join later.", false);
            }

            $this->converting = true;
            try {
                $this->loadChunks($this->loader->getChunkRadius());

                foreach ($this->level->getChunks() as $chunk) {
                    $changed = false;
                    foreach ($chunk->getTiles() as $tile) {
                        if ($tile instanceof Sign) {
                            $convertedSigns++;
                            $colors = Utils::getTextFormatColors();
                            for ($i = 0; $i < 4; $i++) {
                                $s = $tile->getLine($i);
                                $str = "";
                                if (strpos($s, "[") !== false) {
                                    $data = json_decode($s, true)["extra"][0];
                                    if (is_array($data)) {
                                        if (array_key_exists("bold", $data)) {
                                            $str .= TextFormat::BOLD;
                                        }
                                        if (array_key_exists("color", $data)) {
                                            $str .= $colors[$data["color"]];
                                        }
                                        $str .= json_decode('"' . $data["text"] . '"');
                                    } else {
                                        $str = json_decode('"' . $data . '"');
                                    }
                                }
                                $tile->setLine($i, $str);
                                $changed = true;
                            }
                        }
                    }
                    for ($y = 0; $y < $chunk->getMaxY(); $y++) {
                        $subChunk = $chunk->getSubChunk($y >> 4);
                        if (!($subChunk instanceof EmptySubChunk)) {
                            for ($x = 0; $x < 16; $x++) {
                                for ($z = 0; $z < 16; $z++) {
                                    $blockId = $subChunk->getBlockId($x, $y & 0x0f, $z);
                                    if ($blockId !== Block::AIR) {
                                        $blockData = $subChunk->getBlockData($x, $y & 0x0f, $z);
                                        foreach (array_keys($this->loader->getBlocksData()) as $blockVal) {
                                            $split = explode("-", $blockVal);
                                            $configId = (int)$split[0];
                                            $configData = (int)$split[1];

                                            if ($blockId === $configId && ($blockData === $configData || $configData === self::IGNORE_DATA_VALUE)) {
                                                $newId = (int)$this->loader->getBlocksConfig()->getNested("blocks." . $blockVal . ".converted-id");
                                                $newData = (int)$this->loader->getBlocksConfig()->getNested("blocks." . $blockVal . ".converted-data");
                                                $subChunk->setBlock($x, $y & 0x0f, $z, $newId, $newData);
                                                $changed = true;
                                                $convertedBlocks++;
                                            }
                                        }
                                    }
                                }
                            }
                            $subChunksAnalyzed++;
                        }
                    }
                    $chunk->setChanged($changed);
                    $chunksAnalyzed++;
                }

                $this->level->save(true);
            } catch (\Exception $e) {
                $this->loader->getLogger()->critical($e);
                $status = false;
            }

            $this->converting = false;
            $this->loader->getLogger()->debug("Conversion finished! Printing full report...");

            $report = PHP_EOL . "§d--- Conversion Report ---" . PHP_EOL;
            $report .= "§bStatus: " . ($status ? "§2Completed" : "§cAborted") . PHP_EOL;
            $report .= "§bLevel name: §a" . $this->level->getName() . PHP_EOL;
            $report .= "§bExecution time: §a" . floor(microtime(true) - $time_start) . " second(s)" . PHP_EOL;
            $report .= "§bAnalyzed chunks: §a" . $chunksAnalyzed . PHP_EOL;
            $report .= "§bAnalyzed sub-chunks: §a" . $subChunksAnalyzed . PHP_EOL;
            $report .= "§bBlocks converted: §a" . $convertedBlocks . PHP_EOL;
            $report .= "§bSigns converted: §a" . $convertedSigns . PHP_EOL;
            $report .= "§d----------";

            $this->loader->getLogger()->info(Utils::translateColors($report));
        }


    }

    private function startAnalysis(): array
    {
        $errors = [];

        if (!empty($this->loader->getBlocksData())) {
            /**@var string $blockVal */
            foreach (array_keys($this->loader->getBlocksData()) as $blockVal) {
                $blockVal = (string)$blockVal;
                $explode = explode("-", $blockVal);
                if (count($explode) !== 2) {
                    $errors[] = "$blockVal is not a correct configuration value, it should be ID-Data (e.g. 1-0)";
                }
            }
        } else {
            $errors[] = "The configuration key \"blocks\" of blocks.yml file is empty, you could not run the conversion!";
        }

        return $errors;
    }

    private function loadChunks(int $radius): void
    {
        $spawn = $this->level->getSpawnLocation();
        $x = $spawn->getFloorX() >> 4;
        $z = $spawn->getFloorZ() >> 4;

        $this->loader->getLogger()->debug("Loading chunks (radius = " . $radius . ") ...");
        $chunksLoaded = 0;
        for ($chunkX = -$radius; $chunkX <= $radius; $chunkX++) {
            for ($chunkZ = -$radius; $chunkZ <= $radius; $chunkZ++) {
                if (sqrt($chunkX * $chunkX + $chunkZ * $chunkZ) <= $radius) {
                    $this->level->loadChunk($chunkX + $x, $chunkZ + $z);
                    $chunksLoaded++;
                }
            }
        }
        $this->loader->getLogger()->debug($chunksLoaded . " chunks loaded.");
    }
}