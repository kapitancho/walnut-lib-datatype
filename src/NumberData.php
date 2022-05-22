<?php

namespace Walnut\Lib\DataType;

use Attribute;
use Walnut\Lib\DataType\Exception\{InvalidValueRange};
use Walnut\Lib\DataType\Exception\InvalidValueType;
use Walnut\Lib\DataType\Exception\NumberType\{NumberBelowMinimum};
use Walnut\Lib\DataType\Exception\NumberType\NumberAboveMaximum;
use Walnut\Lib\DataType\Exception\NumberType\NumberNotMultipleOf;

/**
 * @package Walnut\Lib\DataType
 * @readonly
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class NumberData implements DirectValue {
	public function __construct(
		public readonly bool $nullable = false,
		public readonly ?float $minimum = null,
		public readonly bool $exclusiveMinimum = false,
		public readonly ?float $maximum = null,
		public readonly bool $exclusiveMaximum = false,
		public readonly ?float $multipleOf = null,
		public readonly ?string $format = null,
	) {
		if (isset($this->minimum, $this->maximum) && $this->minimum > $this->maximum) {
			throw new InvalidValueRange($this->minimum, $this->maximum);
		}
	}

	/**
	 * @throws InvalidValueType|NumberAboveMaximum|NumberBelowMinimum|NumberNotMultipleOf
	 */
	public function importValue(
		null|string|float|int|bool|array|object $value
	): null|float|int {
		$this->validateValue($value);
		return isset($value) ? (float)$value : null;
	}

	/**
	 * @throws InvalidValueType|NumberAboveMaximum|NumberBelowMinimum|NumberNotMultipleOf
	 */
	protected function validateValue(
		null|string|float|int|bool|array|object $value
	): void {
		if (!is_float($value) && !is_int($value) && !($value === null && $this->nullable)) {
			throw new InvalidValueType('double', gettype($value));
		}
		if (isset($value)) {
			$this->tooSmall($value)->tooLarge($value)->notMultipleOf($value);
		}
	}

	/**
	 * @throws NumberBelowMinimum
	 */
	private function tooSmall(int|float $value): self {
		if (isset($this->minimum)) {
			if ($value < $this->minimum || ((float)$value === $this->minimum && $this->exclusiveMinimum)) {
				throw new NumberBelowMinimum($this->minimum, $this->exclusiveMinimum, $value);
			}
		}
		return $this;
	}

	/**
	 * @throws NumberAboveMaximum
	 */
	private function tooLarge(int|float $value): self {
		if (isset($this->maximum)) {
			if ($value > $this->maximum || ((float)$value === $this->maximum && $this->exclusiveMaximum)) {
				throw new NumberAboveMaximum($this->maximum, $this->exclusiveMaximum, $value);
			}
		}
		return $this;
	}

	/**
	 * @throws NumberNotMultipleOf
	 */
	private function notMultipleOf(int|float $value): self {
		if (isset($this->multipleOf) && $value % $this->multipleOf) {
			throw new NumberNotMultipleOf($this->multipleOf, $value);
		}
		return $this;
	}

}
