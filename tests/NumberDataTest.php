<?php

namespace Walnut\Lib\DataType;

use PHPUnit\Framework\TestCase;
use Walnut\Lib\DataType\Exception\InvalidValueRange;
use Walnut\Lib\DataType\Exception\InvalidValueType;
use Walnut\Lib\DataType\Exception\NumberType\NumberAboveMaximum;
use Walnut\Lib\DataType\Exception\NumberType\NumberBelowMinimum;
use Walnut\Lib\DataType\Exception\NumberType\NumberNotMultipleOf;

final class NumberDataTest extends TestCase {

	public function testInvalidValueRange(): void {
		$this->expectException(InvalidValueRange::class);
		(new NumberData(minimum: 5.5, maximum: 3.5));
	}

	public function testInvalidValueType(): void {
		$this->expectException(InvalidValueType::class);
		(new NumberData)->validateValue("TEST");
	}

	public function testAllowNull(): void {
		$this->expectNotToPerformAssertions();
		(new NumberData(nullable: true))->validateValue(null);
	}

	public function testDisallowNull(): void {
		$this->expectException(InvalidValueType::class);
		(new NumberData)->validateValue(null);
	}

	public function testNumberBelowMinimumOk(): void {
		$this->expectNotToPerformAssertions();
		(new NumberData(minimum: 3))->validateValue(3);
		(new NumberData(minimum: 3))->validateValue(4.5);
		(new NumberData(minimum: 3, exclusiveMinimum: true))->validateValue(4.5);
	}

	public function testNumberBelowMinimumError(): void {
		$this->expectException(NumberBelowMinimum::class);
		(new NumberData(minimum: 3))->validateValue(2);
	}

	public function testNumberBelowMinimumExclusiveError(): void {
		$this->expectException(NumberBelowMinimum::class);
		(new NumberData(minimum: 3, exclusiveMinimum: true))->validateValue(3);
	}

	public function testNumberAboveMaximumOk(): void {
		$this->expectNotToPerformAssertions();
		(new NumberData(maximum: 3))->validateValue(3);
		(new NumberData(maximum: 3))->validateValue(2.5);
		(new NumberData(maximum: 3, exclusiveMaximum: true))->validateValue(2.5);
	}

	public function testNumberAboveMaximumError(): void {
		$this->expectException(NumberAboveMaximum::class);
		(new NumberData(maximum: 3))->validateValue(4);
	}

	public function testNumberAboveMaximumExclusiveError(): void {
		$this->expectException(NumberAboveMaximum::class);
		(new NumberData(maximum: 3, exclusiveMaximum: true))->validateValue(3);
	}

	public function testInRange(): void {
		$this->expectNotToPerformAssertions();
		(new NumberData(minimum: 1.5, maximum: 3.5))->validateValue(2);
	}

	public function testNumberNotMultipleOfOk(): void {
		$this->expectNotToPerformAssertions();
		(new NumberData(multipleOf: 5))->validateValue(15);
	}

	public function testNumberNotMultipleOfError(): void {
		$this->expectException(NumberNotMultipleOf::class);
		(new NumberData(multipleOf: 5))->validateValue(8);
	}

	//@TODO - test format

}
