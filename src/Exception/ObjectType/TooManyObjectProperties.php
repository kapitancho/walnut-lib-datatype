<?php

namespace Walnut\Lib\DataType\Exception\ObjectType;

use Walnut\Lib\DataType\Exception\InvalidValue;

/**
 * @package Walnut\Lib\DataType
 */
final class TooManyObjectProperties extends InvalidValue {
	private const ERROR_MESSAGE = "The number of properties in the object '%d' is more than the maximal allowed '%d' properties.";
	public function __construct(
		public /*readonly*/ int $maxProperties,
		public /*readonly*/ int $actualPropertiesCount
	) {
		parent::__construct();
	}

	public function __toString(): string {
		return sprintf(self::ERROR_MESSAGE, $this->actualPropertiesCount, $this->maxProperties);
	}
}
