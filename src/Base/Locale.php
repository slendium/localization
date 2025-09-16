<?php

namespace Slendium\Localization\Base;

use Locale as SplLocale;

use Slendium\Localization\Locale as ILocale;

/**
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2025
 */
class Locale implements ILocale {

	/** @since 1.0 */
	public static function parse(string $locale): ?self {
		$subtags = SplLocale::parseLocale($locale);
		if ($subtags === null || !isset($subtags['language'])) {
			return null;
		}
		$variants = [ ];
		for ($i = 0; ; $i += 1) {
			if (!isset($subtags["variant$i"])) {
				break;
			}
			$variants[] = $subtags["variant$i"];
		}
		return new self(
			language: $subtags['language'], // @phpstan-ignore argument.type
			script: ($subtags['script'] ?? '') !== '' ? $subtags['script'] : null, // @phpstan-ignore argument.type
			region: ($subtags['region'] ?? '') !== '' ? $subtags['region'] : null, // @phpstan-ignore argument.type
			variants: $variants, // @phpstan-ignore argument.type
		);
	}

	/** @since 1.0 */
	public static function defaultLocale(): self {
		return self::parse(SplLocale::getDefault()) ?? new self('en');
	}

	/** @since 1.0 */
	public function __construct(

		/**
		 * @var non-empty-string
		 * @override
		 */
		public readonly string $language,

		/**
		 * @var ?non-empty-string
		 * @override
		 */
		public readonly ?string $script = null,

		/**
		 * @var ?non-empty-string
		 * @override
		 */
		public readonly ?string $region = null,

		/**
		 * @var list<non-empty-string>
		 * @override
		 */
		public readonly array $variants = [ ],

	) { }

	public function __toString(): string {
		$subtags = [ 'language' => $this->language ];
		if ($this->script !== null) { $subtags['script'] = $this->script; }
		if ($this->region !== null) { $subtags['region'] = $this->region; }
		if (\count($this->variants) > 0) { $subtags['variants'] = $this->variants; }
		return SplLocale::canonicalize(SplLocale::composeLocale($subtags)); // @phpstan-ignore argument.type, return.type (cause error deliberately in these cases)
	}

}
