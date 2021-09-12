<?php

namespace Walnut\Lib\DataType\Exception\StringType;

use Walnut\Lib\DataType\Exception\InvalidValue;

/**
 * @package Walnut\Lib\DataType
 */
final class StringIncorrectlyFormatted extends InvalidValue {
	private const ERROR_MESSAGE = "The string '%s' is not in format '%s'.";
	public function __construct(
		public /*readonly*/ string $format,
		public /*readonly*/ string $value
	) {
		parent::__construct();
	}

	public function __toString(): string {
		return sprintf(self::ERROR_MESSAGE, $this->value, $this->format);
	}

}
