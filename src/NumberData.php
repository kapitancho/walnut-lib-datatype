<?php

namespace Walnut\Lib\DataType;

use Attribute;
use Walnut\Lib\DataType\Exception\{InvalidValueRange, InvalidValueType};
use Walnut\Lib\DataType\Exception\NumberType\{
	NumberAboveMaximum, NumberBelowMinimum, NumberNotMultipleOf
};

/**
 * @package Walnut\Lib\DataType
 * @readonly
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class NumberData implements ValueValidator {
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
	 * @param mixed $value
	 * @throws InvalidValueType
	 * @throws NumberAboveMaximum
	 * @throws NumberBelowMinimum
	 * @throws NumberNotMultipleOf
	 */
	public function validateValue(mixed $value): void {
		if (!is_float($value) && !is_int($value) && !($value === null && $this->nullable)) {
			throw new InvalidValueType('double', gettype($value));
		}
		if (isset($value)) {
			$this->tooSmall($value)->tooLarge($value)->notMultipleOf($value);
		}
	}

	private function tooSmall(int|float $value): self {
		if (isset($this->minimum)) {
			if ($value < $this->minimum || ((float)$value === $this->minimum && $this->exclusiveMinimum)) {
				throw new NumberBelowMinimum($this->minimum, $this->exclusiveMinimum, $value);
			}
		}
		return $this;
	}

	private function tooLarge(int|float $value): self {
		if (isset($this->maximum)) {
			if ($value > $this->maximum || ((float)$value === $this->maximum && $this->exclusiveMaximum)) {
				throw new NumberAboveMaximum($this->maximum, $this->exclusiveMaximum, $value);
			}
		}
		return $this;
	}

	private function notMultipleOf(int|float $value): self {
		if (isset($this->multipleOf) && $value % $this->multipleOf) {
			throw new NumberNotMultipleOf($this->multipleOf, $value);
		}
		return $this;
	}

}
