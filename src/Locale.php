<?php

namespace Slendium\Localization;

use Stringable;

/**
 * An object-oriented wrapper around RFC 4646 locale strings.
 * <p>The `__toString()`  method should be equivalent to calling `\Locale::canonicalize()`.</p>
 * @see https://www.php.net/manual/en/class.locale.php
 * @see https://www.php.net/manual/en/locale.canonicalize.php
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2025
 */
interface Locale extends Stringable {

	/**
	 * The primary language of the locale.
	 * @since 1.0
	 * @see https://www.php.net/manual/en/locale.getprimarylanguage.php
	 * @var non-empty-string
	 */
	public string $language { get; }

	/**
	 * The script of the locale, if available.
	 * @since 1.0
	 * @see https://www.php.net/manual/en/locale.getscript.php
	 * @var ?non-empty-string
	 */
	public ?string $script { get; }

	/**
	 * The region of the locale, if available.
	 * @since 1.0
	 * @see https://www.php.net/manual/en/locale.getregion.php
	 * @var ?non-empty-string
	 */
	public ?string $region { get; }

	/**
	 * The variants of the locale, if any.
	 * @since 1.0
	 * @see https://www.php.net/manual/en/locale.getallvariants.php
	 * @var list<non-empty-string>
	 */
	public array $variants { get; }

}
