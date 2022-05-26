<?php

namespace Walnut\Lib\DataType;

use ReflectionException;
use Walnut\Lib\DataType\Exception\{ObjectType\RequiredObjectPropertyMissing, StringType\StringNotInEnum};
use Walnut\Lib\DataType\Exception\InvalidValue;
use Walnut\Lib\DataType\Exception\InvalidValueType;
use Walnut\Lib\DataType\Exception\ObjectType\TooFewObjectProperties;
use Walnut\Lib\DataType\Exception\ObjectType\TooManyObjectProperties;
use Walnut\Lib\DataType\Exception\ObjectType\UnsupportedObjectPropertyFound;

/**
 * @package Walnut\Lib\DataType
 * @readonly
 * @template T of object
 */
final class EnumData implements DirectValue {
	/**
	 * @param class-string<T> $className
	 * @param bool $isInteger
	 * @param non-empty-list<string> $values
	 */
	public function __construct(
		public readonly string $className,
		public readonly bool $isInteger,
		public readonly array $values
	) {}

	/**
	 * @param string|float|int|bool|array|object|null $value
	 * @return T
	 * @throws InvalidValueType|RequiredObjectPropertyMissing|TooFewObjectProperties|TooManyObjectProperties|UnsupportedObjectPropertyFound|InvalidValue|ReflectionException
	 */
	public function importValue(
		null|string|float|int|bool|array|object $value
	): object {
		if ($this->isInteger && !is_int($value)) {
			throw new InvalidValueType('int enum', gettype($value));
		}
		if (!$this->isInteger && !is_string($value)) {
			throw new InvalidValueType('string enum', gettype($value));
		}
		return ($this->className)::tryFrom($value) ??
			throw new StringNotInEnum($this->values, $value);
	}

}
