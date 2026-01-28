<?php

namespace Slendium\Localization\Base;

use ArrayIterator;
use LogicException;
use Override;
use OutOfBoundsException;
use Traversable;

use Slendium\Localization\Locale as ILocale;
use Slendium\Localization\LocaleList as ILocaleList;

/**
 * An immutable list of locales.
 *
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2025
 */
final class LocaleList implements ILocaleList {

	/** @var list<ILocale> */
	private readonly array $locales;

	/** @since 1.0 */
	public static function fromHttpAcceptLanguage(string $acceptLanguage): self {
		$weighted = [ ];
		foreach (\explode(',', \trim($acceptLanguage)) as $locale) {
			if (($weightedLocale = self::parseWeightedLocale($locale)) !== null) {
				$weighted[] = $weightedLocale;
			}
		}
		\usort($weighted, static fn($a, $b) => $b->q <=> $a->q);
		return new self(\array_map(static fn($w) => $w->locale, $weighted));
	}

	/** @return ?object{locale:Locale,q:float} */
	private static function parseWeightedLocale(string $localeString): ?object {
		$locale = null;
		$q = null;
		foreach (\explode(';', \trim($localeString)) as $part) {
			if (\strpos($part, '=') !== false) {
				$q = self::parseQualityValue($part);
			} else {
				$locale = Locale::parse(\trim($part));
			}
		}
		return $locale !== null
			? (object)[ 'locale' => $locale, 'q' => $q ?? 1.0 ]
			: null;
	}

	private static function parseQualityValue(string $qv): ?float {
		$qv = \array_map(\trim(...), \explode('=', \strtolower($qv)));
		return \count($qv) === 2 && $qv[0] === 'q' && \is_numeric($qv[1])
			? (float)$qv[1]
			: null;
	}

	/**
	 * @since 1.0
	 * @param iterable<ILocale> $locales
	 */
	public function __construct(iterable $locales) {
		$result = [ ];
		foreach ($locales as $locale) {
			$result[] = $locale;
		}
		$this->locales = $result;
	}

	#[Override]
	public function offsetExists(mixed $offset): bool {
		return isset($this->locales[$offset]);
	}

	#[Override]
	public function offsetGet(mixed $offset): ILocale {
		return $this->locales[$offset]
			?? throw new OutOfBoundsException("Offset `$offset` is out of bounds for locale list");
	}

	#[Override]
	public function offsetSet(mixed $offset, mixed $value): void {
		throw new LogicException('Locale list is immutable');
	}

	#[Override]
	public function offsetUnset(mixed $offset): void {
		throw new LogicException('Locale list is immutable');
	}

	#[Override]
	public function count(): int {
		return \count($this->locales);
	}

	#[Override]
	public function getIterator(): Traversable {
		return new ArrayIterator($this->locales);
	}

}
