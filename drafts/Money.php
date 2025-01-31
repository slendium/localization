<?php

namespace Slendium\Localization;

/**
 * A monetary value.
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2025
 */
interface Money {

	/**
	 * @since 1.0
	 * @var ?non-empty-string
	 */
	public string $currency { get; }

	/**
	 * @since 1.0
	 * @var numeric-string
	 */
	public string $value { get; }

}
