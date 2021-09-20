<?php

declare(strict_types=1);

namespace matcracker\BlocksConverter\translationMaps;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use pocketmine\block\Block;
use RuntimeException;
use function count;

abstract class BlocksTranslationMap implements ArrayAccess, IteratorAggregate, Countable, JsonSerializable{

	/** @var array<int, int> */
	protected array $map;

	final protected static function toFullBlockId(int $blockId, int $blockMeta) : int{
		return ($blockId << Block::INTERNAL_METADATA_BITS) | $blockMeta;
	}

	final public function offsetExists(mixed $offset) : bool{
		return isset($this->map[$offset]);
	}

	final public function offsetGet(mixed $offset) : int{
		return $this->map[$offset];
	}

	final public function offsetSet(mixed $offset, mixed $value) : void{
		throw new RuntimeException('Attempt to mutate immutable ' . __CLASS__ . ' object.');
	}

	final public function offsetUnset(mixed $offset) : void{
		throw new RuntimeException('Attempt to mutate immutable ' . __CLASS__ . ' object.');
	}

	final public function getIterator() : ArrayIterator{
		return new ArrayIterator($this->map);
	}

	public function count() : int{
		return count($this->map);
	}

	public function jsonSerialize(){
		return $this->map;
	}
}