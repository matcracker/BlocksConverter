<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter;

class LevelQueue
{
    /**@var LevelManager[] $queue */
    private static $queue = [];

    public static function addInQueue(LevelManager $levelManager): void
    {
        self::$queue[$levelManager->getLevel()->getName()] = $levelManager;
    }

    public static function isEmpty(): bool
    {
        return empty(self::$queue);
    }

    public static function removeFromQueue(string $levelName): void
    {
        if (self::isInQueue($levelName)) {
            unset(self::$queue[$levelName]);
        }
    }

    public static function isInQueue(string $levelName): bool
    {
        return isset(self::$queue[$levelName]);
    }

    /**
     * @return LevelManager[]
     */
    public static function getQueue(): array
    {
        return self::$queue;
    }
}