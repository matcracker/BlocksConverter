<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter\world;

use Exception;
use FilesystemIterator;
use Generator;
use InvalidStateException;
use matcracker\BlocksConverter\BlocksMap;
use matcracker\BlocksConverter\Loader;
use matcracker\BlocksConverter\utils\Utils;
use pocketmine\block\BlockIds;
use pocketmine\level\format\Chunk;
use pocketmine\level\format\EmptySubChunk;
use pocketmine\level\format\io\exception\CorruptedChunkException;
use pocketmine\level\format\io\leveldb\LevelDB;
use pocketmine\level\format\io\region\Anvil;
use pocketmine\level\format\io\region\McRegion;
use pocketmine\level\format\io\region\PMAnvil;
use pocketmine\level\Level;
use pocketmine\tile\Sign;
use pocketmine\utils\Binary;
use pocketmine\utils\TextFormat;
use RegexIterator;

class WorldManager
{
	/**@var Loader $loader */
	private $loader;
	/**@var Level $world */
	private $world;
	/**@var bool $isConverting */
	private $isConverting = false;

	public function __construct(Loader $loader, Level $world)
	{
		$this->loader = $loader;
		$this->world = $world;
	}

	public function getWorld(): Level
	{
		return $this->world;
	}

	public function backup(): void
	{
		$this->loader->getLogger()->debug("Creating a backup of {$this->world->getName()}");
		$srcPath = "{$this->loader->getServer()->getDataPath()}/worlds/{$this->world->getFolderName()}";
		$destPath = "{$this->loader->getDataFolder()}/backups/{$this->world->getFolderName()}";
		Utils::recursiveCopyDirectory($srcPath, $destPath);
		$this->loader->getLogger()->debug("Backup successfully created");
	}

	public function restore(): void
	{
		$this->loader->getLogger()->debug("Restoring a backup of {$this->world->getName()}");
		$srcPath = "{$this->loader->getDataFolder()}/backups/{$this->world->getFolderName()}";
		if (!$this->hasBackup()) {
			throw new InvalidStateException("This world never gets a backup.");
		}

		$destPath = "{$this->loader->getServer()->getDataPath()}/worlds/{$this->world->getFolderName()}";

		Utils::recursiveCopyDirectory($srcPath, $destPath);
		$this->loader->getLogger()->debug("Successfully restored");
	}

	public function hasBackup(): bool
	{
		return file_exists("{$this->loader->getDataFolder()}/backups/{$this->world->getFolderName()}");
	}

	public function unloadLevel(): bool
	{
		return $this->loader->getServer()->unloadLevel($this->world);
	}

	public function isConverting(): bool
	{
		return $this->isConverting;
	}

	public function startConversion(): void
	{
		//Conversion report variables
		$status = true;
		$chunksAnalyzed = $subChunksAnalyzed = $convertedBlocks = $convertedSigns = 0;

		$conversionStart = microtime(true);

		if (!$this->hasBackup()) {
			$this->loader->getLogger()->warning("The world \"{$this->world->getName()}\" will be converted without a backup.");
		}

		foreach ($this->loader->getServer()->getOnlinePlayers() as $player) {
			$player->kick("The server is running a world conversion, try to join later.", false);
		}

		$this->loader->getLogger()->debug("Starting world \"{$this->world->getName()}\" conversion...");

		$this->isConverting = true;
		$totalChunks = $this->countChunks();
		try {
			$this->loader->getLogger()->debug("Loading {$totalChunks} chunks...");
			$chunks = $this->loadAllChunks(true);
			$this->loader->getLogger()->debug("Chunks loaded.");
			$blocksMap = BlocksMap::get();

			$chunkTime = microtime(true);
			/**@var Chunk $chunk */
			foreach ($chunks as $chunk) {
				$hasChanged = false;
				for ($y = 0; $y < $chunk->getMaxY(); $y++) {
					$subChunk = $chunk->getSubChunk($y >> 4);
					if ($subChunk instanceof EmptySubChunk) {
						continue;
					}

					for ($x = 0; $x < 16; $x++) {
						for ($z = 0; $z < 16; $z++) {
							$blockId = $subChunk->getBlockId($x, $y & 0x0f, $z);
							if ($blockId === BlockIds::AIR) {
								continue;
							}

							if ($blockId === BlockIds::SIGN_POST || $blockId === BlockIds::WALL_SIGN) {
								$tile = $this->world->getTileAt($x, $y, $z);
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
										$hasChanged = true;
									}
								}
							} else {
								$blockMeta = $subChunk->getBlockData($x, $y & 0x0f, $z);
								foreach ($blocksMap as $oldId => $subMap) {
									foreach ($subMap as $oldMeta => $newBlockData) {
										if ($blockId === $oldId && $blockMeta === $oldMeta) {
											$this->loader->getLogger()->debug("Replaced block \"{$blockId}:{$blockMeta}\" with \"{$newBlockData[0]}:{$newBlockData[1]}\"");
											$subChunk->setBlock($x, $y & 0x0f, $z, $newBlockData[0], $newBlockData[1]);
											$hasChanged = true;
											$convertedBlocks++;
										}
									}
								}
							}
						}
					}
					$subChunksAnalyzed++;
				}

				if ($hasChanged) {
					$this->world->setChunk($chunk->getX(), $chunk->getZ(), $chunk, false);
					$this->world->unloadChunk($chunk->getX(), $chunk->getZ());
				}

				$chunksAnalyzed++;
				if ($chunksAnalyzed % 200 === 0 || $chunksAnalyzed === $totalChunks) {
					$diff = number_format((microtime(true) - $chunkTime), 1);
					$this->loader->getLogger()->info("Current analyzed chunks: {$chunksAnalyzed}/{$totalChunks} (200 chunks/{$diff}s)");
					$chunkTime = microtime(true);
				}
			}

			$this->world->save(true);
		} catch (Exception $e) {
			$this->loader->getLogger()->critical($e);
			$status = false;
		}

		$this->isConverting = false;
		$this->loader->getLogger()->debug("Conversion finished! Printing full report...");

		$report = PHP_EOL . TextFormat::LIGHT_PURPLE . "--- Conversion Report ---" . PHP_EOL;
		$report .= TextFormat::AQUA . "Status: " . ($status ? (TextFormat::DARK_GREEN . "Completed") : (TextFormat::RED . "Aborted")) . PHP_EOL;
		$report .= TextFormat::AQUA . "World name: " . TextFormat::GREEN . $this->world->getName() . PHP_EOL;
		$report .= TextFormat::AQUA . "Execution time: " . TextFormat::GREEN . number_format((microtime(true) - $conversionStart), 1) . " second(s)" . PHP_EOL;
		$report .= TextFormat::AQUA . "Analyzed chunks: " . TextFormat::GREEN . $chunksAnalyzed . PHP_EOL;
		$report .= TextFormat::AQUA . "Analyzed sub-chunks: " . TextFormat::GREEN . $subChunksAnalyzed . PHP_EOL;
		$report .= TextFormat::AQUA . "Blocks converted: " . TextFormat::GREEN . $convertedBlocks . PHP_EOL;
		$report .= TextFormat::AQUA . "Signs converted: " . TextFormat::GREEN . $convertedSigns . PHP_EOL;
		$report .= TextFormat::LIGHT_PURPLE . "----------";

		$this->loader->getLogger()->info($report);
	}

	private function loadAllChunks(bool $skipCorrupted = false): Generator
	{
		$provider = $this->world->getProvider();

		if ($provider instanceof LevelDB) {
			foreach ($provider->getDatabase()->getIterator() as $key => $_) {
				if (strlen($key) === 9 and substr($key, -1) === LevelDB::TAG_VERSION) {
					$chunkX = Binary::readLInt(substr($key, 0, 4));
					$chunkZ = Binary::readLInt(substr($key, 4, 4));
					try {
						if (($chunk = $provider->loadChunk($chunkX, $chunkZ)) !== null) {
							yield $chunk;
						}
					} catch (CorruptedChunkException $e) {
						if (!$skipCorrupted) {
							throw $e;
						}
					}
				}
			}
		} else {
			foreach ($this->createRegionIterator() as $region) {
				$regionX = ((int)$region[1]);
				$regionZ = ((int)$region[2]);
				$rX = $regionX << 5;
				$rZ = $regionZ << 5;
				for ($chunkX = $rX; $chunkX < $rX + 32; ++$chunkX) {
					for ($chunkZ = $rZ; $chunkZ < $rZ + 32; ++$chunkZ) {
						try {
							$chunk = $provider->loadChunk($chunkX, $chunkZ);
							if ($chunk !== null) {
								yield $chunk;
							}
						} catch (CorruptedChunkException $e) {
							if (!$skipCorrupted) {
								throw $e;
							}
						}
					}
				}
			}
		}
	}

	private function countChunks(): int
	{
		$provider = $this->world->getProvider();
		$count = 0;
		if ($provider instanceof LevelDB) {
			foreach ($provider->getDatabase()->getIterator() as $key => $_) {
				if (strlen($key) === 9 && substr($key, -1) === LevelDB::TAG_VERSION) {
					$count++;
				}
			}
		} else {
			foreach ($this->createRegionIterator() as $region) {
				$regionX = ((int)$region[1]);
				$regionZ = ((int)$region[2]);
				$rX = $regionX << 5;
				$rZ = $regionZ << 5;
				for ($chunkX = $rX; $chunkX < $rX + 32; ++$chunkX) {
					for ($chunkZ = $rZ; $chunkZ < $rZ + 32; ++$chunkZ) {
						if ($this->world->isChunkGenerated($chunkX, $chunkZ)) {
							$this->world->unloadChunk($chunkX, $chunkZ, false, false);
							$count++;
						}
					}
				}
			}
		}
		return $count;
	}

	private function createRegionIterator(): RegexIterator
	{
		return new RegexIterator(
			new FilesystemIterator(
				$this->world->getProvider()->getPath() . 'region/',
				FilesystemIterator::CURRENT_AS_PATHNAME | FilesystemIterator::SKIP_DOTS | FilesystemIterator::UNIX_PATHS
			),
			'/\/r\.(-?\d+)\.(-?\d+)\.' . $this->getWorldExtension() . '$/',
			RegexIterator::GET_MATCH
		);
	}

	private function getWorldExtension(): ?string
	{
		$providerName = $this->world->getProvider()->getProviderName();
		if ($providerName === Anvil::getProviderName()) {
			return Anvil::REGION_FILE_EXTENSION;
		} else if ($providerName === McRegion::getProviderName()) {
			return McRegion::REGION_FILE_EXTENSION;
		} else if ($providerName === PMAnvil::getProviderName()) {
			return PMAnvil::REGION_FILE_EXTENSION;
		}

		return null;
	}
}