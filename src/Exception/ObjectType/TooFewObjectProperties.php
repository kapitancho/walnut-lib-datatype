<?php

namespace Walnut\Lib\DataType\Exception\ObjectType;

use Walnut\Lib\DataType\Exception\InvalidValue;

/**
 * @package Walnut\Lib\DataType
 */
final class TooFewObjectProperties extends InvalidValue {
	private const ERROR_MESSAGE = "The number of properties in the object '%d' is less than the minimal allowed '%d' properties.";
	public function __construct(
		public readonly int $minProperties,
		public readonly int $actualPropertiesCount
	) {
		parent::__construct();
	}

	public function __toString(): string {
		return sprintf(self::ERROR_MESSAGE, $this->actualPropertiesCount, $this->minProperties);
	}
}