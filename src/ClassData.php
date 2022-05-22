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
#[Attribute(Attribute::TARGET_PROPERTY)]
final class ClassData implements CompositeValue {
	/**
	 * @param class-string<T> $className
	 * @param string[] $required
	 * @param array<string, DirectValue|CompositeValue|ClassRef> $properties
	 */
	public function __construct(
		public readonly string $className,
		public readonly array $required = [],
		public readonly array $properties = []
	) {}

	/**
	 * @param string|float|int|bool|array|object|null $value
	 * @param CompositeValueHydrator $nestedValueHydrator
	 * @return ?T
	 * @throws InvalidValueType|RequiredObjectPropertyMissing|TooFewObjectProperties|TooManyObjectProperties|UnsupportedObjectPropertyFound|InvalidValue|ReflectionException
	 */
	public function importValue(
		null|string|float|int|bool|array|object $value,
		CompositeValueHydrator $nestedValueHydrator
	): ?object {
		$values = $this->cleanValue($value);
		return (new ReflectionClass($this->className))->newInstance(...
			$this->importValues($values, $nestedValueHydrator)
		);
	}

	/**
	 * @return array<string, null|string|float|int|bool|array|object>
	 * @throws InvalidValueType
	 */
	protected function cleanValue(
		null|string|float|int|bool|array|object $value
	): array {
		/**
		 * @var array<string, null|string|float|int|bool|array|object>
		 */
		return match(true) {
			is_object($value) => get_object_vars($value),
			is_array($value) && (!array_is_list($value) || $value === []) => $value,
			default => throw new InvalidValueType('object', gettype($value))
		};
	}

	/**
	 * @param array<string, int|float|bool|string|array|object|null> $values
	 * @param CompositeValueHydrator $nestedValueImporter
	 * @return array<string, int|float|bool|string|array|object|null>
	 * @throws RequiredObjectPropertyMissing|UnsupportedObjectPropertyFound|InvalidValue
	 */
	protected function importValues(array $values, CompositeValueHydrator $nestedValueImporter): array {
		/**
		 * @var array<string, int|float|bool|string|array|object|null> $result
		 */
		$result = [];

		foreach($values as $prop => $value) {
			$propertyValueImporter = $this->properties[$prop] ??
				throw new UnsupportedObjectPropertyFound($prop);
			$importResult = $nestedValueImporter->importNestedValue($value, $propertyValueImporter, $prop);
			$result[$prop] = $importResult;
		}
		foreach($this->properties as $propertyName => $valueImporter) {
			if (
				!array_key_exists($propertyName, $result) &&
				in_array($propertyName, $this->required, true)
			) {
				throw new RequiredObjectPropertyMissing($propertyName);
			}
		}
		return $result;
	}


}
