<?php

namespace Walnut\Lib\DataType\Importer;

use PHPUnit\Framework\TestCase;
use Walnut\Lib\DataType\ClassRefHydrator;
use Walnut\Lib\DataType\Exception\InvalidValueType;
use Walnut\Lib\DataType\RefValue;

final class RefValueTest extends TestCase {

	protected function setUp(): void {
		$this->importer = $this->createMock(ClassRefHydrator::class);
	}

	public function testAllowNull(): void {
		$this->assertNull((new RefValue(targetClass: \stdClass::class, nullable: true))->importValue(null, $this->importer));
	}

	public function testDisallowNull(): void {
		$this->expectException(InvalidValueType::class);
		(new RefValue(targetClass: \stdClass::class))->importValue(null, $this->importer);
	}

	public function testValues(): void {
		$this->assertIsObject((new RefValue(targetClass: \stdClass::class))->importValue(new \stdClass, $this->importer));
	}

}
