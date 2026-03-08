<?php

namespace Slendium\Localization\Base;

use ArrayAccess;
use IteratorAggregate;
use LogicException;
use Override;
use Traversable;

use Slendium\Localization\Locale;

/**
 * @internal
 * @template T
 * @implements ArrayAccess<Locale,T>
 * @implements IteratorAggregate<Locale,T>
 * @author C. Fahner
 * @copyright Slendium 2025-2026
 */
final class LocaleMap implements ArrayAccess, IteratorAggregate {

	/** @var array<string,Locale> */
	private array $keyMap;

	/** @var array<string,T> */
	private array $valueMap;

	/** @param iterable<Locale,T> $values */
	public function __construct(iterable $values) {
		$keyMap = [ ];
		$valueMap = [ ];
		foreach ($values as $locale => $value) {
			if (($key = (string)$locale) !== '') {
				$keyMap[$key] = $locale;
				$valueMap[$key] = $value;
			}
		}
		$this->keyMap = $keyMap;
		$this->valueMap = $valueMap;
	}

	#[Override]
	public function offsetExists(mixed $offset): bool {
		return isset($this->valueMap[(string)$offset]);
	}

	#[Override]
	public function offsetGet(mixed $offset): mixed {
		$locale = (string)$offset;
		return isset($this->valueMap[$locale])
			? $this->valueMap[$locale]
			: null;
	}

	#[Override]
	public function offsetSet(mixed $offset, mixed $value): void {
		if ($offset === null) {
			throw new LogicException('Cannot append a LocaleMap');
		}
		$locale = (string)$offset;
		$this->keyMap[$locale] = $offset;
		$this->valueMap[$locale] = $value;
	}

	#[Override]
	public function offsetUnset(mixed $offset): void {
		$locale = (string)$offset;
		unset($this->keyMap[$locale], $this->valueMap[$locale]);
	}

	#[Override]
	public function getIterator(): Traversable {
		foreach ($this->valueMap as $key => $value) {
			yield $this->keyMap[$key] => $value;
		}
	}

}
