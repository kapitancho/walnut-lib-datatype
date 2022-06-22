<?php

namespace Walnut\Lib\DataType\Importer;

use PHPUnit\Framework\TestCase;
use Walnut\Lib\DataType\AnyData;
use Walnut\Lib\DataType\CompositeValueHydrator;
use Walnut\Lib\DataType\Exception\InvalidValueType;
use Walnut\Lib\DataType\WrapperData;

final class WrapperDataTest extends TestCase {

	public function testOk(): void {
		$this->assertInstanceOf(WrapperData::class, new WrapperData);
	}

}
