<?php

namespace Slendium\Localization\Base;

use Iterator;
use LogicException;
use Override;
use Traversable;

use Slendium\Localization\Locale;
use Slendium\Localization\LocaleList;
use Slendium\Localization\Localizable;

/**
 * An immutable localizable object whose input is not iterated until a value is accessed from it.
 *
 * @since 1.0
 * @template T
 * @implements Localizable<T>
 * @author C. Fahner
 * @copyright Slendium 2026
 */
final class LazyLocalizable implements Localizable {

	/**
	 * A static construction method.
	 *
	 * This method returns the interface instead of the concrete type, making it more convenient to
	 * use with static analyzers.
	 *
	 * For use with first class callable syntax, ie. `LazyLocalizable::create(...)`.
	 *
	 * @since 1.0
	 * @template TCreate
	 * @param iterable<Locale,TCreate> $values
	 * @return Localizable<TCreate>
	 */
	public static function create(iterable $values): Localizable {
		return new self($values);
	}

	/** @var LocaleMap<T> */
	private LocaleMap $map {
		get => $this->map ??= $this->init_map();
	}

	/** @var ?iterable<Locale,T> */
	private ?iterable $values;

	/**
	 * @since 1.0
	 * @param iterable<Locale,T> $values
	 */
	public function __construct(

		iterable $values,

		/** @override */
		public readonly mixed $fallback = null,

	) {
		$this->values = $values;
	}

	#[Override]
	public function offsetExists(mixed $offset): bool {
		if ($offset instanceof Locale) {
			$offset = [ $offset ];
		}
		foreach ($offset as $locale) {
			if (isset($this->map[$locale])) {
				return true;
			}
		}
		return false;
	}

	#[Override]
	public function offsetGet(mixed $offset): mixed {
		if ($offset instanceof Locale) {
			$offset = [ $offset ];
		}
		foreach ($offset as $locale) {
			if (isset($this->map[$locale])) {
				return $this->map[$locale];
			}
		}
		return null;
	}

	#[Override]
	public function offsetSet(mixed $offset, mixed $value): void {
		throw new LogicException('Cannot modify a lazy localizable');
	}

	#[Override]
	public function offsetUnset(mixed $offset): void {
		throw new LogicException('Cannot modify a lazy localizable');
	}

	#[Override]
	public function getIterator(): Traversable {
		yield from $this->map;
	}

	/** @return self<T> */
	#[Override]
	public function withFallback(mixed $fallback): self {
		return new self($this->getIterator(), $fallback);
	}

	/** @return LocaleMap<T> */
	private function init_map(): LocaleMap {
		$map = new LocaleMap($this->values); // @phpstan-ignore argument.type (this->values is never NULL here)
		$this->values = null;
		return $map;
	}

}
