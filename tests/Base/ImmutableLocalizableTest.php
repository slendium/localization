<?php

namespace Slendium\LocalizationTests;

use Throwable;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

use Slendium\Localization\Base\ImmutableLocalizable;
use Slendium\Localization\Base\Locale;
use Slendium\Localization\Base\LocaleList;

final class ImmutableLocalizableTest extends TestCase {

	public static function getOffsetCases(): iterable {
		$sut = ImmutableLocalizable::fromMap([ 'nl' => 1 ]);
		yield [ $sut, new Locale('nl'), 1 ];
		yield [ $sut, new Locale('en'), null ];
		yield [ $sut, new LocaleList([ new Locale('en'), new Locale('nl') ]), 1 ];
		yield [ $sut, new LocaleList([ new Locale('uk'), new Locale('ru') ]), null ];
	}

	#[DataProvider('getOffsetCases')]
	public function test_offsetIsset(ImmutableLocalizable $sut, $offset, $expected): void {
		$this->assertSame($expected !== null, isset($sut[$offset]));
	}

	#[DataProvider('getOffsetCases')]
	public function test_offsetGet(ImmutableLocalizable $sut, $offset, $expected): void {
		$this->assertSame($expected, $sut[$offset]);
	}

	public function test_offsetSet_shouldThrow(): void {
		// Arrange
		$sut = new ImmutableLocalizable([ ]);

		// Assert
		$this->expectException(Throwable::class);

		// Act
		$sut[new Locale('fy')] = 1;
	}

	public function test_offsetUnset_shouldThrow(): void {
		// Arrange
		$sut = new ImmutableLocalizable([ ]);

		// Assert
		$this->expectException(Throwable::class);

		// Act
		$sut[new Locale('fy')] = 1;
	}

	public function test_getIterator_shouldMatchConstructorIterable(): void {
		// Arrange
		$locale1 = new Locale('nl');
		$locale2 = new Locale('en');
		$sut = new ImmutableLocalizable($this->createLocaleIterable($locale1, 1, $locale2, 2));

		// Act
		$iterations = 0;
		foreach ($sut as $locale => $value) {
			// Assert
			if ($locale === $locale1) { $this->assertSame(1, $value); }
			if ($locale === $locale2) { $this->assertSame(2, $value); }
			$iterations += 1;
		}
		$this->assertSame(2, $iterations);
	}

	public function test_filter_shouldHideNonMatchingElements(): void {
		$sut = new ImmutableLocalizable($this->createLocaleIterable(new Locale('nl'), 1, new Locale('en'), 2));

		$result = $sut->filter(static fn($v) => $v === 1);

		$this->assertSame(1, $result[new Locale('nl')]);
		$this->assertNull($result[new Locale('en')]);
	}

	public function test_transform_shouldApplyToEachElement(): void {
		$sut = new ImmutableLocalizable($this->createLocaleIterable(new Locale('nl'), 1, new Locale('en'), 2));

		$result = $sut->transform(static fn($v) => $v*2);

		$this->assertSame(2, $result[new Locale('nl')]);
		$this->assertSame(4, $result[new Locale('en')]);
	}

	public function test_withFallback_shouldCreateNewLocalizableWithGivenFallback(): void {
		$sut = new ImmutableLocalizable([ ]);

		$result = $sut->withFallback(1);

		$this->assertNull($sut->fallback);
		$this->assertSame(1, $result->fallback);
		$this->assertNotSame($sut, $result);
	}

	private function createLocaleIterable(Locale $loc1, mixed $val1, Locale $loc2, mixed $val2): iterable {
		yield $loc1 => $val1;
		yield $loc2 => $val2;
	}

}
