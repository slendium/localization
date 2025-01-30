<?php

namespace Slendium\Localization;

use ArrayAccess, Closure, IteratorAggregate;

/**
 * Provides access to the localized variants of an item.
 * @since 1.0
 * @template T
 * @extends ArrayAccess<Locale|string,T>
 * @extends IteratorAggregate<string,T>
 * @author C. Fahner
 * @copyright Slendium 2025
 */
interface Localizable extends ArrayAccess, IteratorAggregate {

	/**
	 * The current fallback value, if any.
	 * @since 1.0
	 * @see ::withFallback()
	 */
	public mixed $fallback { get; }

	/**
	 * Applies a filter to the localizable.
	 * @since 1.0
	 * @param Closure(T,string $locale):bool $filter The filter function to apply
	 * @return self<T> A new localizable that will only return a value that passes the given filter
	 */
	public function filter(Closure $filter): self;

	/**
	 * Applies a transformation to this localizable.
	 * <p>The transformation function is only called for the value that was found.</p>
	 * @since 1.0
	 * @template TOut
	 * @param Closure(T,string $locale):TOut $transform The transform function to apply
	 * @return self<TOut> A new localizable that will result in the transformed value
	 */
	public function transform(Closure $transform): self;

	/**
	 * Sets the `$fallback` property.
	 * @since 1.0
	 * @param mixed $fallback The fallback value to use if a lookup fails
	 * @return self<T> A new localizable that uses the given fallback
	 */
	public function withFallback(mixed $fallback): self;

	/**
	 * A method that can be used to optimize localizables based on expensive database queries.
	 * <p>Iterating a localizable should still result in a full fetch of all possible variants,
	 * even if this method was called beforehand. It can merely be used as an indication from the
	 * implementation that it will soon try to access the locales from the given list and that they
	 * could be prefetched in a single operation.</p>
	 * @since 1.0
	 * @param Locale[] $locales The locales about to be accessed, in no particular order
	 */
	public function prepareLocales(array $locales): void;

}
