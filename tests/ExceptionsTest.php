<?php

namespace Walnut\Lib\DataType;

use PHPUnit\Framework\TestCase;
use Walnut\Lib\DataType\Exception\ArrayType\ArrayElementsNotUnique;
use Walnut\Lib\DataType\Exception\ArrayType\TooFewArrayElements;
use Walnut\Lib\DataType\Exception\ArrayType\TooManyArrayElements;
use Walnut\Lib\DataType\Exception\InvalidData;
use Walnut\Lib\DataType\Exception\InvalidValueRange;
use Walnut\Lib\DataType\Exception\InvalidValueType;
use Walnut\Lib\DataType\Exception\NumberType\NumberAboveMaximum;
use Walnut\Lib\DataType\Exception\NumberType\NumberBelowMinimum;
use Walnut\Lib\DataType\Exception\NumberType\NumberNotMultipleOf;
use Walnut\Lib\DataType\Exception\ObjectType\RequiredObjectPropertyMissing;
use Walnut\Lib\DataType\Exception\ObjectType\TooFewObjectProperties;
use Walnut\Lib\DataType\Exception\ObjectType\TooManyObjectProperties;
use Walnut\Lib\DataType\Exception\ObjectType\UnsupportedObjectPropertyFound;
use Walnut\Lib\DataType\Exception\StringType\StringIncorrectlyFormatted;
use Walnut\Lib\DataType\Exception\StringType\StringNotInEnum;
use Walnut\Lib\DataType\Exception\StringType\StringTooLong;
use Walnut\Lib\DataType\Exception\StringType\StringTooShort;

final class ExceptionsTest extends TestCase {

	public function testExceptions(): void {
		$this->assertStringContainsString('path', (new InvalidData("path", 'value', new InvalidValueType('a', 'b'))));
		$this->assertStringContainsString('value', (new InvalidData("path", 'value', new InvalidValueType('a', 'b'))));

		$this->assertStringContainsString('5', (new InvalidValueType('5', '3')));
		$this->assertStringContainsString('3', (new InvalidValueType('5','3')));

		$this->assertStringContainsString('5', (new InvalidValueRange(5, 3)));
		$this->assertStringContainsString('3', (new InvalidValueRange(5,3)));

		$this->assertStringContainsString('3', (new ArrayElementsNotUnique(3)));
		$this->assertStringContainsString('5', (new TooFewArrayElements(5, 3)));
		$this->assertStringContainsString('3', (new TooFewArrayElements(5, 3)));
		$this->assertStringContainsString('5', (new TooManyArrayElements(5, 7)));
		$this->assertStringContainsString('7', (new TooManyArrayElements(5, 7)));

		$this->assertStringContainsString('5', (new TooFewObjectProperties(5, 3)));
		$this->assertStringContainsString('3', (new TooFewObjectProperties(5, 3)));
		$this->assertStringContainsString('5', (new TooManyObjectProperties(5, 7)));
		$this->assertStringContainsString('7', (new TooManyObjectProperties(5, 7)));
		$this->assertStringContainsString('test', (new RequiredObjectPropertyMissing('test')));
		$this->assertStringContainsString('test', (new UnsupportedObjectPropertyFound('test')));

		$this->assertStringContainsString('5', (new NumberBelowMinimum(5, false, 3)));
		$this->assertStringContainsString('3', (new NumberBelowMinimum(5, false, 3)));
		$this->assertStringContainsString('5', (new NumberAboveMaximum(5, false, 7)));
		$this->assertStringContainsString('7', (new NumberAboveMaximum(5, false, 7)));
		$this->assertStringContainsString('5', (new NumberNotMultipleOf(5, 7)));
		$this->assertStringContainsString('7', (new NumberNotMultipleOf(5, 7)));

		$this->assertStringContainsString('5', (new StringTooShort(5, '3')));
		$this->assertStringContainsString('3', (new StringTooShort(5,'3')));
		$this->assertStringContainsString('5', (new StringTooLong(5, '7')));
		$this->assertStringContainsString('7', (new StringTooLong(5, '7')));
		$this->assertStringContainsString('2', (new StringNotInEnum(['1', '2'], '5')));
		$this->assertStringContainsString('5', (new StringNotInEnum(['1', '2'], '5')));
		$this->assertStringContainsString('5', (new StringIncorrectlyFormatted('5', '7')));
		$this->assertStringContainsString('7', (new StringIncorrectlyFormatted('5', '7')));
	}
}
