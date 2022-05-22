<?php

namespace Walnut\Lib\DataType\Importer;

use PHPUnit\Framework\TestCase;
use Walnut\Lib\DataType\AnyData;
use Walnut\Lib\DataType\AnyOfData;
use Walnut\Lib\DataType\CompositeValueHydrator;
use Walnut\Lib\DataType\Exception\InvalidData;
use Walnut\Lib\DataType\Exception\InvalidValueType;

final class AnyOfDataTest extends TestCase {

	protected function setUp(): void {
		$this->importer = $this->createMock(CompositeValueHydrator::class);
	}
	
	public function testAllowNull(): void {
		$this->assertNull((new AnyOfData(true, new AnyData))
			->importValue(null, $this->importer));
	}

	public function testDisallowNull(): void {
		$this->expectException(InvalidValueType::class);
		(new AnyOfData(false, new AnyData))->importValue(null, $this->importer);
	}

	public function testAllValid(): void {
		$this->importer->method('importNestedValue')->willReturn(null);
		$this->assertNull((new AnyOfData(false, new AnyData))->importValue(1, $this->importer));
	}

	public function testAllInvalid(): void {
		$this->expectException(InvalidData::class);
		$this->importer->method('importNestedValue')->willThrowException(new InvalidData("path", 'value', new InvalidValueType('a', 'b')));
		$this->assertNull((new AnyOfData(false, new AnyData))->importValue(1, $this->importer));
	}

}
