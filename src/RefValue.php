<?php

namespace Walnut\Lib\DataType;

use Attribute;
use Walnut\Lib\DataType\Exception\InvalidValue;
use Walnut\Lib\DataType\Exception\InvalidValueType;

/**
 * @package Walnut\Lib\DataType
 * @readonly
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class RefValue implements ClassRef {
	/**
	 * @param class-string $targetClass
	 */
	public function __construct(
		public readonly string $targetClass,
		public readonly bool $nullable = false
	) {}

	/**
	 * @throws InvalidValue
	 */
	public function importValue(
		null|string|float|int|bool|array|object $value,
		ClassRefHydrator $refValueHydrator
	): ?object {
		if ($value === null && !$this->nullable) {
			throw new InvalidValueType('object', gettype($value));
		}
		if (!isset($value)) {
			return null;
		}
		return $refValueHydrator->importRefValue($value, $this->targetClass);
	}

}
