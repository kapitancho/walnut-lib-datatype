<?php

namespace Walnut\Lib\DataType\Exception\StringType;

use Walnut\Lib\DataType\Exception\InvalidValue;

/**
 * @package Walnut\Lib\DataType
 */
final class StringNotInEnum extends InvalidValue {
	private const ERROR_MESSAGE = "The string '%s' is not in the list of allowed values '%s'.";

	/**
	 * @param string[] $enumValues
	 * @param string $value
	 */
	public function __construct(
		public readonly array $enumValues,
		public readonly string $value
	) {
		parent::__construct();
	}

	public function __toString(): string {
		return sprintf(self::ERROR_MESSAGE, $this->value, implode(', ', $this->enumValues));
	}

}
