<?php

namespace Walnut\Lib\DataType;

use Attribute;
use Walnut\Lib\DataType\Exception\InvalidValueType;
use Walnut\Lib\DataType\Exception\NumberType\{NumberBelowMinimum};
use Walnut\Lib\DataType\Exception\NumberType\NumberAboveMaximum;
use Walnut\Lib\DataType\Exception\NumberType\NumberNotMultipleOf;

/**
 * @package Walnut\Lib\DataType
 * @readonly
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class IntegerData extends NumberData {
	/**
	 * @throws InvalidValueType|NumberAboveMaximum|NumberBelowMinimum|NumberNotMultipleOf
	 */
	public function importValue(
		null|string|float|int|bool|array|object $value
	): ?int {
		if (!is_int($value) && !($value === null && $this->nullable)) {
			throw new InvalidValueType('integer', gettype($value));
		}
		$this->validateValue($value);
		return $value ?? null;
	}
}
