<?php

namespace Walnut\Lib\DataType\Exception\StringType;

use Walnut\Lib\DataType\Exception\InvalidValue;

/**
 * @package Walnut\Lib\DataType
 */
final class StringTooShort extends InvalidValue {
	private const ERROR_MESSAGE = "The length of the string '%d' is less than the minimal allowed length of '%d'.";
	public function __construct(
		public /*readonly*/ int $minLength,
		public /*readonly*/ int $actualLength
	) {
		parent::__construct();
	}

	public function __toString(): string {
		return sprintf(self::ERROR_MESSAGE, $this->actualLength, $this->minLength);
	}

}
