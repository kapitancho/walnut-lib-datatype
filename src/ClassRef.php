<?php

namespace Walnut\Lib\DataType;

use Walnut\Lib\DataType\Exception\InvalidData;

/**
 * @package Walnut\Lib\DataType
 */
interface ClassRef {
	/**
	 * @throws InvalidData
	 */
	public function importValue(
		null|string|float|int|bool|array|object $value,
		ClassRefHydrator $refValueHydrator
	): null|string|float|int|bool|array|object;
}



