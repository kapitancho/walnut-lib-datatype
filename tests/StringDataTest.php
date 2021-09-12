<?php

namespace Walnut\Lib\DataType;

use PHPUnit\Framework\TestCase;
use Walnut\Lib\DataType\Exception\InvalidValueRange;
use Walnut\Lib\DataType\Exception\InvalidValueType;
use Walnut\Lib\DataType\Exception\StringType\StringIncorrectlyFormatted;
use Walnut\Lib\DataType\Exception\StringType\StringNotInEnum;
use Walnut\Lib\DataType\Exception\StringType\StringTooLong;
use Walnut\Lib\DataType\Exception\StringType\StringTooShort;

final class StringDataTest extends TestCase {

	public function testInvalidValueRange(): void {
		$this->expectException(InvalidValueRange::class);
		(new StringData(minLength: 5, maxLength: 3));
	}

	public function testInvalidValueType(): void {
		$this->expectException(InvalidValueType::class);
		(new StringData)->validateValue(5);
	}

	public function testAllowNull(): void {
		$this->expectNotToPerformAssertions();
		(new StringData(nullable: true))->validateValue(null);
	}

	public function testDisallowNull(): void {
		$this->expectException(InvalidValueType::class);
		(new StringData)->validateValue(null);
	}

	public function testTooFewOk(): void {
		$this->expectNotToPerformAssertions();
		(new StringData(minLength: 3))->validateValue("123");
		(new StringData(minLength: 3))->validateValue("1234");
	}

	public function testTooFewError(): void {
		$this->expectException(StringTooShort::class);
		(new StringData(minLength: 3))->validateValue("12");
	}

	public function testTooManyOk(): void {
		$this->expectNotToPerformAssertions();
		(new StringData(maxLength: 3))->validateValue("123");
		(new StringData(maxLength: 3))->validateValue("12");
	}

	public function testTooManyError(): void {
		$this->expectException(StringTooLong::class);
		(new StringData(maxLength: 3))->validateValue("1234");
	}

	public function testInRange(): void {
		$this->expectNotToPerformAssertions();
		(new StringData(minLength: 1, maxLength: 3))->validateValue("12");
	}

	public function testInEnum(): void {
		$this->expectNotToPerformAssertions();
		//No uniqueness requirement
		(new StringData(enum: ["1", "2", "3"]))->validateValue("1");
	}

	public function testNotInEnum(): void {
		$this->expectException(StringNotInEnum::class);
		(new StringData(enum: ["1", "2", "3"]))->validateValue("4");
	}

	public function testDateTimeFormatOk(): void {
		$this->expectNotToPerformAssertions();
		//No uniqueness requirement
		(new StringData(format: StringData::FORMAT_DATE_TIME))->validateValue("2000-01-01T12:00:00Z");
	}

	public function testDateTimeFormatError(): void {
		$this->expectException(StringIncorrectlyFormatted::class);
		(new StringData(format: StringData::FORMAT_DATE_TIME))->validateValue("2000-01-03");
	}

	public function testSkipUnknownFormat(): void {
		$this->expectNotToPerformAssertions();
		//No uniqueness requirement
		(new StringData(format: 'unknown'))->validateValue("2000-01-01T12:00:00Z");
	}

	//@TODO - pattern

}
