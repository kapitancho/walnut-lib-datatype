<?php

namespace Walnut\Lib\DataType;

use Attribute;
use Walnut\Lib\DataType\Exception\InvalidValueType;

/**
 * @package Walnut\Lib\DataType
 * @readonly
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class AnyData implements ValueValidator {
	public function __construct(
		public /*readonly*/ bool $nullable = false,
	) {}

	/**
	 * @param mixed $value
	 * @throws InvalidValueType
	 */
	public function validateValue(mixed $value): void {
		if ($value === null && !$this->nullable) {
			throw new InvalidValueType('mixed', gettype($value));
		}
	}
}
