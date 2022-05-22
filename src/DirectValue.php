<?php

namespace Walnut\Lib\DataType;

use Walnut\Lib\DataType\Exception\InvalidValue;

/**
 * @package Walnut\Lib\DataType
 */
interface DirectValue {
	/**
	 * @throws InvalidValue
	 */
	public function importValue(
		null|string|float|int|bool|array|object $value
	): null|string|float|int|bool|array|object;
}
