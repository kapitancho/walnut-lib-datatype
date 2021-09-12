<?php

namespace Walnut\Lib\DataType;

use PHPUnit\Framework\TestCase;
use Walnut\Lib\DataType\Exception\InvalidValueRange;
use Walnut\Lib\DataType\Exception\InvalidValueType;
use Walnut\Lib\DataType\Exception\ObjectType\RequiredObjectPropertyMissing;
use Walnut\Lib\DataType\Exception\ObjectType\TooFewObjectProperties;
use Walnut\Lib\DataType\Exception\ObjectType\TooManyObjectProperties;

final class ObjectDataTest extends TestCase {

	public function testInvalidValueRange(): void {
		$this->expectException(InvalidValueRange::class);
		(new ObjectData(minProperties: 5, maxProperties: 3));
	}

	public function testInvalidValueType(): void {
		$this->expectException(InvalidValueType::class);
		(new ObjectData)->validateValue(5);
	}

	public function testAllowNull(): void {
		$this->expectNotToPerformAssertions();
		(new ObjectData(nullable: true))->validateValue(null);
	}

	public function testDisallowNull(): void {
		$this->expectException(InvalidValueType::class);
		(new ObjectData)->validateValue(null);
	}

	public function testTooFewOk(): void {
		$this->expectNotToPerformAssertions();
		(new ObjectData(minProperties: 3))->validateValue((object)[1, 2, 3]);
		(new ObjectData(minProperties: 3))->validateValue((object)[1, 2, 3, 4]);
	}

	public function testTooFewError(): void {
		$this->expectException(TooFewObjectProperties::class);
		(new ObjectData(minProperties: 3))->validateValue((object)[1, 2]);
	}

	public function testTooManyOk(): void {
		$this->expectNotToPerformAssertions();
		(new ObjectData(maxProperties: 3))->validateValue((object)[1, 2, 3]);
		(new ObjectData(maxProperties: 3))->validateValue((object)[1, 2]);
	}

	public function testTooManyError(): void {
		$this->expectException(TooManyObjectProperties::class);
		(new ObjectData(maxProperties: 3))->validateValue((object)[1, 2, 3, 4]);
	}

	public function testInRange(): void {
		$this->expectNotToPerformAssertions();
		(new ObjectData)->validateValue((object)[1, 2]);
		(new ObjectData(minProperties: 1, maxProperties: 3))->validateValue((object)[1, 2]);
	}

	public function testRequiredOk(): void {
		$this->expectNotToPerformAssertions();
		(new ObjectData(required: ['a', 'b']))->validateValue((object)['a' => 1, 'b' => 2, 'c' => 3]);
	}

	public function testRequiredError(): void {
		$this->expectException(RequiredObjectPropertyMissing::class);
		(new ObjectData(required: ['a', 'b']))->validateValue((object)['a' => 1, 'c' => 3]);
	}

}
