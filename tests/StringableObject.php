<?php

namespace Slendium\LocalizationTests;

class StringableObject {

	public function __construct(private readonly string $stringValue) { }

	public function __toString(): string {
		return $this->stringValue;
	}

}
