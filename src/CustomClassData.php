<?php

namespace Walnut\Lib\DataType;

use Attribute;
use ReflectionClass;
use ReflectionException;
use Walnut\Lib\DataType\Exception\{ObjectType\RequiredObjectPropertyMissing};
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
final class CustomClassData implements CompositeValue {
	/**
	 * @param class-string<T> $className
	 * @param DirectValue $propertyValue
	 */
	public function __construct(
		public readonly string $className,
		public readonly DirectValue $propertyValue
	) {}

	/**
	 * @param string|float|int|bool|array|object|null $value
	 * @param CompositeValueHydrator $nestedValueHydrator
	 * @return T
	 * @throws ReflectionException
	 */
	public function importValue(
		null|string|float|int|bool|array|object $value,
		CompositeValueHydrator $nestedValueHydrator
	): null|string|float|int|bool|array|object {
		return $this->propertyValue->importValue($value);
	}


}
