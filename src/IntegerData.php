<?php

namespace Walnut\Lib\DataType;

use Attribute;
use Walnut\Lib\DataType\Exception\InvalidValueType;
use Walnut\Lib\DataType\Exception\NumberType\{
	NumberAboveMaximum, NumberBelowMinimum, NumberNotMultipleOf
};

/**
 * @package Walnut\Lib\DataType
 * @readonly
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class IntegerData extends NumberData {
	/**
	 * @param mixed $value
	 * @throws InvalidValueType
	 * @throws NumberAboveMaximum
	 * @throws NumberBelowMinimum
	 * @throws NumberNotMultipleOf
	 */
	public function validateValue(mixed $value): void {
		if (!is_int($value) && !($value === null && $this->nullable)) {
			throw new InvalidValueType('integer', gettype($value));
		}
		parent::validateValue($value);
	}
}
