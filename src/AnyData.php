<?php

namespace Walnut\Lib\DataType;

use Attribute;
use Walnut\Lib\DataType\Exception\InvalidValueType;

/**
 * @package Walnut\Lib\DataType
 * @readonly
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class AnyData implements DirectValue {
	public function __construct(
		public readonly bool $nullable = false,
	) {}

	/**
	 * @throws InvalidValueType
	 */
	public function importValue(
		null|string|float|int|bool|array|object $value
	): null|string|float|int|bool|array|object {
		if ($value === null && !$this->nullable) {
			throw new InvalidValueType('mixed', gettype($value));
		}
		return $value;
	}
}
