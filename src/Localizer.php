<?php

namespace Slendium\Localization;

/**
 * Converts any {@see Localizable} to it's localized variant.
 *
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
interface Localizer {

	/**
	 * @since 1.0
	 * @template T
	 * @param Localizable<T> $localizable
	 * @return T
	 */
	public function localize(Localizable $localizable): mixed;

}
