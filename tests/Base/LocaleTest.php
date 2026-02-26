<?php

namespace Slendium\LocalizationTests\Base;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

use Slendium\Localization\Base\Locale;
use Slendium\LocalizationTests\StringableObject;

final class LocaleTest extends TestCase {

	public function test_fromMixed_shouldReturnSameInstance(): void {
		$locale = Locale::defaultLocale();

		$result = Locale::fromMixed($locale);

		$this->assertSame($locale, $result);
	}

	public function test_fromMixed_shouldParse_whenStringGiven(): void {
		$expectedLanguage = 'en';
		$expectedRegion = 'US';
		$locale = "{$expectedLanguage}_{$expectedRegion}";

		$result = Locale::fromMixed($locale);

		$this->assertSame($expectedLanguage, $result->language);
		$this->assertSame($expectedRegion, $result->region);
	}

	public function test_fromMixed_shouldParse_whenStringableGiven(): void {
		$expectedLanguage = 'nl';
		$expectedRegion = 'BE';
		$locale = "{$expectedLanguage}_{$expectedRegion}";

		$result = Locale::fromMixed(new StringableObject($locale));

		$this->assertSame($expectedLanguage, $result->language);
		$this->assertSame($expectedRegion, $result->region);
	}

	public function test_fromMixed_shouldReturnNull_whenInvalidStringGiven(): void {
		$result = Locale::fromMixed('_US');

		$this->assertNull($result);
	}

}
