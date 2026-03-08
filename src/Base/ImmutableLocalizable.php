<?php

namespace Slendium\Localization\Base;

use Iterator;
use LogicException;
use Override;

use Slendium\Localization\Locale as ILocale;
use Slendium\Localization\LocaleList;
use Slendium\Localization\Localizable;

/**
 * @since 1.0
 * @template T
 * @implements Localizable<T>
 * @author C. Fahner
 * @copyright Slendium 2025
 */
final class ImmutableLocalizable implements Localizable {

	/** @var array<non-empty-string,ILocale> */
	private readonly array $keyMap;

	/** @var array<non-empty-string,T> */
	private readonly array $valueMap;

	/**
	 * @since 1.0
	 * @template TIn
	 * @param iterable<non-empty-string,TIn> $map
	 * @return self<TIn>
	 */
	public static function fromMap(iterable $map): self {
		return new self(self::generateLocaleIterable($map));
	}

	/**
	 * @template TIn
	 * @param iterable<non-empty-string,TIn> $iterable
	 * @return iterable<Locale,TIn>
	 */
	private static function generateLocaleIterable(iterable $iterable): iterable {
		foreach ($iterable as $locale => $value) {
			if (($locale = Locale::parse($locale)) !== null) {
				yield $locale => $value;
			}
		}
	}

	/**
	 * @since 1.0
	 * @param iterable<ILocale,T> $values
	 */
	public function __construct(

		iterable $values,

		/** @override */
		public readonly mixed $fallback = null,

	) {
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
		if ($offset instanceof ILocale) {
			$offset = [ $offset ];
		}
		foreach ($offset as $locale) {
			if (isset($this->valueMap[(string)$locale])) {
				return true;
			}
		}
		return false;
	}

	#[Override]
	public function offsetGet(mixed $offset): mixed {
		if ($offset instanceof ILocale) {
			$offset = [ $offset ];
		}
		foreach ($offset as $locale) {
			$locale = (string)$locale;
			if (isset($this->valueMap[$locale])) {
				return $this->valueMap[$locale];
			}
		}
		return null;
	}

	#[Override]
	public function offsetSet(mixed $offset, mixed $value): void {
		throw new LogicException('Cannot modify an immutable localizable');
	}

	#[Override]
	public function offsetUnset(mixed $offset): void {
		throw new LogicException('Cannot modify an immutable localizable');
	}

	#[Override]
	public function getIterator(): Iterator {
		return $this->getGenerator();
	}

	/** @return self<T> */
	#[Override]
	public function withFallback(mixed $fallback): self {
		return new self($this->getGenerator(), $fallback);
	}

	/** @return Iterator<ILocale,T> */
	private function getGenerator(): Iterator {
		foreach ($this->valueMap as $key => $value) {
			yield $this->keyMap[$key] => $value;
		}
	}

}
