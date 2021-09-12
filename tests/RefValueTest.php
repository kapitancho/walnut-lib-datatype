<?php

namespace Walnut\Lib\DataType;

use PHPUnit\Framework\TestCase;
use Walnut\Lib\DataType\Exception\InvalidValueType;

final class RefValueTest extends TestCase {

	public function testAllowNull(): void {
		$this->expectNotToPerformAssertions();
		(new RefValue(targetClass: \stdClass::class, nullable: true))->validateValue(null);
	}

	public function testDisallowNull(): void {
		$this->expectException(InvalidValueType::class);
		(new RefValue(targetClass: \stdClass::class))->validateValue(null);
	}

	public function testValues(): void {
		$this->expectNotToPerformAssertions();
		(new RefValue(targetClass: \stdClass::class))->validateValue(new \stdClass);
	}

}
