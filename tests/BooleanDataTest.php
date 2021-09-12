<?php

namespace Walnut\Lib\DataType;

use PHPUnit\Framework\TestCase;
use Walnut\Lib\DataType\Exception\InvalidValueRange;
use Walnut\Lib\DataType\Exception\InvalidValueType;
use Walnut\Lib\DataType\Exception\BooleanType\BooleanAboveMaximum;
use Walnut\Lib\DataType\Exception\BooleanType\BooleanBelowMinimum;
use Walnut\Lib\DataType\Exception\BooleanType\BooleanNotMultipleOf;

final class BooleanDataTest extends TestCase {

	public function testInvalidValueType(): void {
		$this->expectException(InvalidValueType::class);
		(new BooleanData)->validateValue("TEST");
	}

	public function testAllowNull(): void {
		$this->expectNotToPerformAssertions();
		(new BooleanData(nullable: true))->validateValue(null);
	}

	public function testDisallowNull(): void {
		$this->expectException(InvalidValueType::class);
		(new BooleanData)->validateValue(null);
	}

	public function testValues(): void {
		$this->expectNotToPerformAssertions();
		(new BooleanData)->validateValue(true);
		(new BooleanData)->validateValue(false);
	}

}
