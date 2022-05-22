<?php

namespace Walnut\Lib\DataType;

use Walnut\Lib\DataType\Exception\InvalidData;

/**
 * @package Walnut\Lib\DataType
 */
interface ClassRefHydrator {
	/**
	 * @template T of object
	 * @param string|float|int|bool|array|object|null $value
	 * @param class-string<T> $targetClass
	 * @return T
	 * @throws InvalidData
	 */
	public function importRefValue(
		null|string|float|int|bool|array|object $value,
		string $targetClass
	): object;
}



