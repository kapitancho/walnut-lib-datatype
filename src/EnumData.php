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
	 * @param EnumDataType $type
	 * @param non-empty-list<string>|non-empty-list<int> $values
	 */
	public function __construct(
		public readonly string $className,
		public readonly EnumDataType $type,
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
		if ($this->type === EnumDataType::INT && !is_int($value)) {
			throw new InvalidValueType('int enum', gettype($value));
		}
		if ($this->type === EnumDataType::STRING && !is_string($value)) {
			throw new InvalidValueType('string enum', gettype($value));
		}
		if ($this->type === EnumDataType::UNIT && !is_string($value)) {
			throw new InvalidValueType('unit enum', gettype($value));
		}
		if ($this->type !== EnumDataType::UNIT) {
			return ($this->className)::tryFrom($value) ??
				throw new StringNotInEnum($this->values, (string)$value);
		}
		foreach(($this->className)::cases() as $case) {
			if ($case->name === $value) {
				return $case;
			}
		}
		throw new StringNotInEnum($this->values, $value);
	}

}
