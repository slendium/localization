<?php

namespace Slendium\Localization;

use ArrayAccess;
use IteratorAggregate;

/**
 * Provides access to the localized variants of an item.
 *
 * The pipe operator and generator functions that operate on iterables can be combined to transform
 * localizables efficiently, for example:
 *
 * ```php
 * $result = $localizable
 *   |> iterable_filter(?, fn($item) => !$item->isExpired)
 *   |> iterable_map(?, fn($item) => $item->toSomethingElse())
 *   |> LazyLocalizable::create(?);
 * ```
 *
 * Note that the above example transforms all values in the localizable. Consider using a wrapper
 * object (or another closure) if the transformation is expensive or if only the end result needs
 * to be transformed.
 *
 * @since 1.0
 * @template T
 * @extends ArrayAccess<LocaleList|Locale,?T>
 * @extends IteratorAggregate<Locale,T>
 * @author C. Fahner
 * @copyright Slendium 2025-2026
 */
interface Localizable extends ArrayAccess, IteratorAggregate {

	/**
	 * The current fallback value, if any.
	 * @since 1.0
	 * @see ::withFallback()
	 */
	public mixed $fallback { get; }

	/**
	 * Sets the `$fallback` property.
	 * @since 1.0
	 * @param mixed $fallback The fallback value to use if a lookup fails
	 * @return self<T> A new localizable that uses the given fallback
	 */
	public function withFallback(mixed $fallback): self;

}
