<?php

namespace Walnut\Lib\DataType;

use Attribute;
use Walnut\Lib\DataType\Exception\InvalidValueType;

/**
 * @package Walnut\Lib\DataType
 * @readonly
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class RefValue implements ValueValidator {
	/**
	 * @param class-string $targetClass
	 */
	public function __construct(
		public /*readonly*/ string $targetClass,
		public /*readonly*/ bool $nullable = false
	) {}

	/**
	 * @throws InvalidValueType
	 */
	public function validateValue(mixed $value): void {
		if ($value === null && !$this->nullable) {
			throw new InvalidValueType('object', gettype($value));
		}
	}

}
