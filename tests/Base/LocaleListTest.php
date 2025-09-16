<?php

namespace Slendium\LocalizationTests\Base;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

use Slendium\Localization\Base\LocaleList;

final class LocaleListTest extends TestCase {

	public static function getAcceptLanguageCases(): iterable {
		yield [ 'nl, en', [ 'nl', 'en' ] ];
		yield [ 'nl;q=.4, en-US', [ 'en_US', 'nl' ] ];
		yield [ 'nl;q=.6, en-US ; q=0.5', [ 'nl', 'en_US' ] ];
		yield [ 'ru , en;q=.1', [ 'ru', 'en' ] ];
		yield [ 'nl ; q=.5, en, fy; q=.8', [ 'en', 'fy', 'nl' ] ];
	}

	#[DataProvider('getAcceptLanguageCases')]
	public function test_fromHttpAcceptLanguage(string $accept, array $expected): void {
		// Arrange
		$sut = LocaleList::fromHttpAcceptLanguage($accept);
		// Act
		$result = \array_map(\strval(...), \iterator_to_array($sut, preserve_keys: false));
		// Assert
		$this->assertSame($expected, $result);
	}

}
