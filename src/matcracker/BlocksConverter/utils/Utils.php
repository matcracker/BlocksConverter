<?php
declare(strict_types=1);

namespace matcracker\BlocksConverter\utils;

use pocketmine\utils\TextFormat;
use ReflectionClass;

class Utils{
	public static function recursiveCopyDirectory(string $src, string $dst) : void{
		$dir = opendir($src);
		@mkdir($dst);
		while(($file = readdir($dir)) !== false){
			if($file !== "." && $file !== ".."){
				if(is_dir($src . DIRECTORY_SEPARATOR . $file)){
					self::recursiveCopyDirectory($src . DIRECTORY_SEPARATOR . $file, $dst . DIRECTORY_SEPARATOR . $file);
				}else{
					copy($src . DIRECTORY_SEPARATOR . $file, $dst . DIRECTORY_SEPARATOR . $file);
				}
			}
		}
		closedir($dir);
	}

	public static function getTextFormatColors() : array{
		$reflection = new ReflectionClass(TextFormat::class);

		return array_change_key_case($reflection->getConstants(), CASE_LOWER);
	}
}
