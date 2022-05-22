<?php

namespace Walnut\Lib\DataType;

use Walnut\Lib\DataType\Exception\InvalidData;

/**
 * @package Walnut\Lib\DataType
 */
interface CompositeValueHydrator {
	/**
	 * @throws InvalidData
	 */
	public function importNestedValue(
		null|string|float|int|bool|array|object $value,
		DirectValue|CompositeValue|ClassRef     $importer,
		string|int|null                         $key = null
	): null|string|float|int|bool|array|object;
}