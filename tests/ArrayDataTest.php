<?php

namespace Walnut\Lib\DataType;

use PHPUnit\Framework\TestCase;
use Walnut\Lib\DataType\Exception\ArrayType\ArrayElementsNotUnique;
use Walnut\Lib\DataType\Exception\ArrayType\TooFewArrayElements;
use Walnut\Lib\DataType\Exception\ArrayType\TooManyArrayElements;
use Walnut\Lib\DataType\Exception\InvalidValueRange;
use Walnut\Lib\DataType\Exception\InvalidValueType;

final class ArrayDataTest extends TestCase {

	public function testInvalidValueRange(): void {
		$this->expectException(InvalidValueRange::class);
		(new ArrayData(minItems: 5, maxItems: 3));
	}

	public function testInvalidValueType(): void {
		$this->expectException(InvalidValueType::class);
		(new ArrayData)->validateValue(5);
	}

	public function testAllowNull(): void {
		$this->expectNotToPerformAssertions();
		(new ArrayData(nullable: true))->validateValue(null);
	}

	public function testDisallowNull(): void {
		$this->expectException(InvalidValueType::class);
		(new ArrayData)->validateValue(null);
	}

	public function testTooFewOk(): void {
		$this->expectNotToPerformAssertions();
		(new ArrayData(minItems: 3))->validateValue([1, 2, 3]);
		(new ArrayData(minItems: 3))->validateValue([1, 2, 3, 4]);
	}

	public function testTooFewError(): void {
		$this->expectException(TooFewArrayElements::class);
		(new ArrayData(minItems: 3))->validateValue([1, 2]);
	}

	public function testTooManyOk(): void {
		$this->expectNotToPerformAssertions();
		(new ArrayData(maxItems: 3))->validateValue([1, 2, 3]);
		(new ArrayData(maxItems: 3))->validateValue([1, 2]);
	}

	public function testTooManyError(): void {
		$this->expectException(TooManyArrayElements::class);
		(new ArrayData(maxItems: 3))->validateValue([1, 2, 3, 4]);
	}

	public function testInRange(): void {
		$this->expectNotToPerformAssertions();
		(new ArrayData(minItems: 1, maxItems: 3))->validateValue([1, 2]);
	}

	public function testUnique(): void {
		$this->expectNotToPerformAssertions();
		//No uniqueness requirement
		(new ArrayData(uniqueItems: false))->validateValue([1, 1, 2]);
		//Unique elements
		(new ArrayData(uniqueItems: true))->validateValue([1, 2, 3]);

	}

	public function testArrayElementsNotUnique(): void {
		$this->expectException(ArrayElementsNotUnique::class);
		(new ArrayData(uniqueItems: true))->validateValue([1, 1, 2]);
	}

}
