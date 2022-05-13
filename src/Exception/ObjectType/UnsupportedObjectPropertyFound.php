<?php

namespace Walnut\Lib\DataType\Exception\ObjectType;

use Walnut\Lib\DataType\Exception\InvalidValue;

/**
 * @package Walnut\Lib\DataType
 */
final class UnsupportedObjectPropertyFound extends InvalidValue {
	private const ERROR_MESSAGE = "An unsupported property '%s' was found.";
	public function __construct(
		public readonly string $propertyName
	) {
		parent::__construct();
	}

	public function __toString(): string {
		return sprintf(self::ERROR_MESSAGE, $this->propertyName);
	}
}
