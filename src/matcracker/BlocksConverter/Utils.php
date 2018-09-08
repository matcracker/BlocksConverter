<?php
declare(strict_types=1);

namespace matcracker\BlocksConverter;

use pocketmine\utils\TextFormat;

class Utils
{
    public static function copyDirectory(string $src, string $dst): void
    {
        $dir = opendir($src);
        @mkdir($dst);
        while (($file = readdir($dir)) !== false) {
            if (($file !== ".") && ($file !== "..")) {
                if (is_dir($src . DIRECTORY_SEPARATOR . $file)) {
                    self::copyDirectory($src . DIRECTORY_SEPARATOR . $file, $dst . DIRECTORY_SEPARATOR . $file);
                } else {
                    copy($src . DIRECTORY_SEPARATOR . $file, $dst . DIRECTORY_SEPARATOR . $file);
                }
            }
        }
        closedir($dir);
    }

    public static function translateColors(string $message): string
    {
        return preg_replace_callback("/(\\\&|\&)[0-9a-fk-or]/", function (array $matches): string {
            return str_replace(TextFormat::RESET, TextFormat::RESET . TextFormat::WHITE, str_replace("\\" . TextFormat::ESCAPE, "&", str_replace("&", TextFormat::ESCAPE, $matches[0])));
        }, $message);
    }

    public static function getTextFormatColors(): array
    {
        try {
            $reflection = new \ReflectionClass(TextFormat::class);
            return array_change_key_case($reflection->getConstants(), CASE_LOWER);
        } catch (\ReflectionException $e) {
            Loader::getInstance()->getLogger()->error($e->getMessage());
        }
        return array();
    }
}
