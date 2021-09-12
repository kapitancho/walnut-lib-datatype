<?php

namespace Walnut\Lib\DataType;

use Walnut\Lib\DataType\Exception\InvalidValue;

/**
 * @package Walnut\Lib\DataType
 */
interface ValueValidator {
	/**
	 * @param mixed $value
	 * @throws InvalidValue
	 */
	public function validateValue(mixed $value): void;
}
