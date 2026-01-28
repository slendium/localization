<?php

namespace Slendium\Localization;

use ArrayAccess;
use IteratorAggregate;

/**
 * Provides access to the localized variants of an item.
 *
 * @since 1.0
 * @template T
 * @extends ArrayAccess<LocaleList|Locale,?T>
 * @extends IteratorAggregate<Locale,T>
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
	 * @param callable(T,Locale):bool $predicate The filter function to apply
	 * @return self<T> A new localizable that will only return a value that passes the given filter
	 */
	public function filter(callable $predicate): self;

	/**
	 * Applies a transformation to this localizable.
	 * <p>The transformation function is only called for the value that was found.</p>
	 * @since 1.0
	 * @template TOut
	 * @param callable(T,Locale):TOut $transform The transform function to apply
	 * @return self<TOut> A new localizable that will result in the transformed value
	 */
	public function transform(callable $transform): self;

	/**
	 * Sets the `$fallback` property.
	 * @since 1.0
	 * @param mixed $fallback The fallback value to use if a lookup fails
	 * @return self<T> A new localizable that uses the given fallback
	 */
	public function withFallback(mixed $fallback): self;

}
