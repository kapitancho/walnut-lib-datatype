<?php

namespace Walnut\Lib\DataType\Exception\ArrayType;

use Walnut\Lib\DataType\Exception\InvalidValue;

/**
 * @package Walnut\Lib\DataType
 */
final class TooManyArrayElements extends InvalidValue {
	private const ERROR_MESSAGE = "The number of elements in the array '%d' is more than the maximal required '%d' elements.";
	public function __construct(
		public /*readonly*/ int $maxItems,
		public /*readonly*/ int $actualItemsCount
	) {
		parent::__construct();
	}

	public function __toString(): string {
		return sprintf(self::ERROR_MESSAGE, $this->actualItemsCount, $this->maxItems);
	}

}
