<?php

namespace Slendium\Localization;

use ArrayAccess;
use Countable;
use IteratorAggregate;

/**
 * An ordered priority list of locales.
 *
 * @since 1.0
 * @extends ArrayAccess<int<0,max>,Locale>
 * @extends IteratorAggregate<Locale>
 * @author C. Fahner
 * @copyright Slendium 2025
 */
interface LocaleList extends ArrayAccess, Countable, IteratorAggregate { }
