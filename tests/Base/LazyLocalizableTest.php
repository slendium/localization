<?php

namespace Slendium\LocalizationTests;

use Throwable;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

use Slendium\Localization\Base\LazyLocalizable;
use Slendium\Localization\Base\Locale;
use Slendium\Localization\Base\LocaleList;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2025-2026
 */
final class LazyLocalizableTest extends TestCase {

	public static function getOffsetCases(): iterable {
		$sut = LazyLocalizable::create((function () { yield new Locale('nl') => 1; })());
		yield [ $sut, new Locale('nl'), 1 ];
		yield [ $sut, new Locale('en'), null ];
		yield [ $sut, new LocaleList([ new Locale('en'), new Locale('nl') ]), 1 ];
		yield [ $sut, new LocaleList([ new Locale('uk'), new Locale('ru') ]), null ];
	}

	#[DataProvider('getOffsetCases')]
	public function test_offsetIsset(LazyLocalizable $sut, $offset, $expected): void {
		$this->assertSame($expected !== null, isset($sut[$offset]));
	}

	#[DataProvider('getOffsetCases')]
	public function test_offsetGet(LazyLocalizable $sut, $offset, $expected): void {
		$this->assertSame($expected, $sut[$offset]);
	}

	public function test_offsetSet_shouldThrow(): void {
		$sut = new LazyLocalizable((function () { return; yield null; })());

		$this->expectException(Throwable::class);

		$sut[new Locale('fy')] = 1;
	}

	public function test_offsetUnset_shouldThrow(): void {
		$sut = new LazyLocalizable((function () { return; yield null; })());

		$this->expectException(Throwable::class);

		$sut[new Locale('fy')] = 1;
	}

	public function test_getIterator_shouldMatchConstructorIterable(): void {
		// Arrange
		$locale1 = new Locale('nl');
		$locale2 = new Locale('en');
		$sut = new LazyLocalizable($this->createLocaleIterable($locale1, 1, $locale2, 2));

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

	public function test_withFallback_shouldNotTriggerIteration(): void {
		$called = 0;
		$sut = new LazyLocalizable((function () use (&$called) {
			$called += 1;
			yield new Locale('fy') => 1;
		})());

		$result = $sut->withFallback(2);

		$this->assertSame(0, $called);
		$this->assertNull($sut->fallback);
		$this->assertSame(2, $result->fallback);
		$this->assertNotSame($sut, $result);
	}

	public function test_offsetExists_shouldTriggerIteration(): void {
		// Arrange
		$called = 0;
		$generator = (function () use (&$called): iterable {
			$called += 1;
			yield new Locale('fy') => 1;
		})();
		$sut = new LazyLocalizable($generator);

		// Assert
		$this->assertSame(0, $called);

		// Act
		isset($sut[new Locale('fy')]);
		isset($sut[new Locale('nl')]);

		// Assert
		$this->assertSame(1, $called);
	}

	public function test_offsetGet_shouldTriggerIteration(): void {
		// Arrange
		$called = 0;
		$generator = (function () use (&$called): iterable {
			$called += 1;
			yield new Locale('fy') => 1;
		})();
		$sut = new LazyLocalizable($generator);

		// Assert
		$this->assertSame(0, $called);

		// Act
		$sut[new Locale('fy')];
		$sut[new Locale('nl')];

		// Assert
		$this->assertSame(1, $called);
	}

	public function test_getIterator_shouldTriggerIteration(): void {
		// Arrange
		$called = 0;
		$generator = (function () use (&$called): iterable {
			$called += 1;
			yield new Locale('fy') => 1;
		})();
		$sut = new LazyLocalizable($generator);

		// Assert
		$this->assertSame(0, $called);

		// Act
		foreach ($sut as $item) { }
		foreach ($sut as $item) { }

		// Assert
		$this->assertSame(1, $called);
	}

	private function createLocaleIterable(Locale $loc1, mixed $val1, Locale $loc2, mixed $val2): iterable {
		yield $loc1 => $val1;
		yield $loc2 => $val2;
	}

}
