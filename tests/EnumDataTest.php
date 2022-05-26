<?php

namespace Walnut\Lib\DataType\Importer;

use PHPUnit\Framework\TestCase;
use Walnut\Lib\DataType\EnumData;
use Walnut\Lib\DataType\CompositeValueHydrator;
use Walnut\Lib\DataType\Exception\InvalidValueType;
use Walnut\Lib\DataType\Exception\StringType\StringNotInEnum;

enum EnumDataInt: int { case A = 1; case C = 3;}
enum EnumDataString: string { case A = 'z'; case C = 'x';}

final class EnumDataTest extends TestCase {

	public function testIntEnum(): void {
		$enumData = new EnumData(EnumDataInt::class, true, [1, 3]);
		$this->assertEquals(EnumDataInt::C, $enumData->importValue(3));
	}

	public function testStringEnum(): void {
		$enumData = new EnumData(EnumDataString::class, false, ['z', 'x']);
		$this->assertEquals(EnumDataString::C, $enumData->importValue('x'));
	}

	public function testInvalidIntEnum(): void {
		$this->expectException(InvalidValueType::class);
		$enumData = new EnumData(EnumDataInt::class, true, [1, 3]);
		$enumData->importValue('x');
	}

	public function testInvalidIntEnumValue(): void {
		$this->expectException(StringNotInEnum::class);
		$enumData = new EnumData(EnumDataInt::class, true, [1, 3]);
		$enumData->importValue(2);
	}

	public function testInvalidStringEnum(): void {
		$this->expectException(InvalidValueType::class);
		$enumData = new EnumData(EnumDataString::class, false, ['z', 'x']);
		$enumData->importValue(2);
	}

	public function testInvalidStringEnumValue(): void {
		$this->expectException(StringNotInEnum::class);
		$enumData = new EnumData(EnumDataString::class, false, ['z', 'x']);
		$enumData->importValue('y');
	}

}