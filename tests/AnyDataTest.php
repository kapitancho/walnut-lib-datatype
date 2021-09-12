<?php

namespace Walnut\Lib\DataType;

use PHPUnit\Framework\TestCase;
use Walnut\Lib\DataType\Exception\InvalidValueRange;
use Walnut\Lib\DataType\Exception\InvalidValueType;
use Walnut\Lib\DataType\Exception\AnyType\AnyAboveMaximum;
use Walnut\Lib\DataType\Exception\AnyType\AnyBelowMinimum;
use Walnut\Lib\DataType\Exception\AnyType\AnyNotMultipleOf;

final class AnyDataTest extends TestCase {

	public function testAllowNull(): void {
		$this->expectNotToPerformAssertions();
		(new AnyData(nullable: true))->validateValue(null);
	}

	public function testDisallowNull(): void {
		$this->expectException(InvalidValueType::class);
		(new AnyData)->validateValue(null);
	}

	public function testValues(): void {
		$this->expectNotToPerformAssertions();
		(new AnyData)->validateValue(true);
		(new AnyData)->validateValue(false);
		(new AnyData)->validateValue(0);
		(new AnyData)->validateValue(0.5);
		(new AnyData)->validateValue("STRING");
		(new AnyData)->validateValue([]);
		(new AnyData)->validateValue([1]);
		(new AnyData)->validateValue(new \stdClass);
	}

}
