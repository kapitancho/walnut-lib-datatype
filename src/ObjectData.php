<?php

namespace Walnut\Lib\DataType;

use Attribute;
use Walnut\Lib\DataType\Exception\{InvalidValueRange};
use Walnut\Lib\DataType\Exception\InvalidValue;
use Walnut\Lib\DataType\Exception\InvalidValueType;
use Walnut\Lib\DataType\Exception\ObjectType\{RequiredObjectPropertyMissing};
use Walnut\Lib\DataType\Exception\ObjectType\TooFewObjectProperties;
use Walnut\Lib\DataType\Exception\ObjectType\TooManyObjectProperties;
use Walnut\Lib\DataType\Exception\ObjectType\UnsupportedObjectPropertyFound;

/**
 * @package Walnut\Lib\DataType
 * @readonly
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class ObjectData implements CompositeValue {
	/**
	 * ObjectValue constructor.
	 * @param bool $nullable
	 * @param string[] $required
	 * @param int|null $minProperties
	 * @param int|null $maxProperties
	 * @param array<string, DirectValue|CompositeValue|ClassRef> $properties
	 * @param DirectValue|CompositeValue|ClassRef|null $additionalProperties
	 */
	public function __construct(
		public readonly bool $nullable = false,
		public readonly array $required = [],
		public readonly ?int $minProperties = null,
		public readonly ?int $maxProperties = null,
		public readonly array $properties = [],
		public readonly DirectValue|CompositeValue|ClassRef|null $additionalProperties = null,
	) {
		if (isset($this->minProperties, $this->maxProperties) && $this->minProperties > $this->maxProperties) {
			throw new InvalidValueRange($this->minProperties, $this->maxProperties);
		}		
	}

	/**
	 * @throws InvalidValueType|RequiredObjectPropertyMissing|TooFewObjectProperties|TooManyObjectProperties|UnsupportedObjectPropertyFound|InvalidValue
	 */
	public function importValue(
		null|string|float|int|bool|array|object $value,
		CompositeValueHydrator $nestedValueHydrator
	): ?object {
		$values = $this->validateAndCleanValue($value);
		return $values === null ? null :
			(object)$this->importValues($values, $nestedValueHydrator);
	}

	/**
	 * @param float|object|int|bool|array|string|null $value
	 * @return null|array<string, null|string|float|int|bool|array|object>
	 * @throws InvalidValueType|RequiredObjectPropertyMissing|TooFewObjectProperties|TooManyObjectProperties
	 */
	private function validateAndCleanValue(
		float|object|int|bool|array|string|null $value
	): ?array {
		$values = match(true) {
			is_object($value) => get_object_vars($value),
			is_array($value) && (!array_is_list($value) || $value === []) => $value,
			default => null
		};
		if ($values === null) {
			if ($value === null && $this->nullable) {
				return null;
			}
			throw new InvalidValueType('object', gettype($value));
		}
		$l = count($values);
		$this->tooFew($l)->tooMany($l)->requiredMissing($values);
		/**
		 * @var array<string, null|string|float|int|bool|array|object>
		 */
		return $values;
	}

	/**
	 * @param array<string, int|float|bool|string|array|object|null> $values
	 * @param CompositeValueHydrator $nestedValueImporter
	 * @return array<array-key, int|float|bool|string|array|object|null>
	 * @throws InvalidValueType|RequiredObjectPropertyMissing|TooFewObjectProperties|TooManyObjectProperties|UnsupportedObjectPropertyFound|InvalidValue
	 */
	private function importValues(array $values, CompositeValueHydrator $nestedValueImporter): array {
		/**
		 * @var array<array-key, int|float|bool|string|array|object|null> $result
		 */
		$result = [];

		foreach($values as $prop => $value) {
			$propertyValueImporter = $this->properties[$prop] ?? $this->additionalProperties ??
				throw new UnsupportedObjectPropertyFound($prop);
			$importResult = $nestedValueImporter->importNestedValue($value, $propertyValueImporter, $prop);
			$result[$prop] = $importResult;
		}
		foreach($this->properties as $propertyName => $valueImporter) {
			if (!array_key_exists($propertyName, $result)) {
				$result[$propertyName] = $nestedValueImporter->importNestedValue(
					null, $valueImporter, $propertyName
				);
			}
		}
		return $result;
	}

	/**
	 * @throws TooFewObjectProperties
	 */
	private function tooFew(int $l): self {
		if (isset($this->minProperties) && $l < $this->minProperties) {
			throw new TooFewObjectProperties($this->minProperties, $l);
		}
		return $this;
	}

	/**
	 * @throws TooManyObjectProperties
	 */
	private function tooMany(int $l): self {
		if (isset($this->maxProperties) && $l > $this->maxProperties) {
			throw new TooManyObjectProperties($this->maxProperties, $l);
		}
		return $this;
	}

	/**
	 * @throws RequiredObjectPropertyMissing
	 */
	private function requiredMissing(array $value): self {
		foreach($this->required as $required) {
			if (!array_key_exists($required, $value)) {
				throw new RequiredObjectPropertyMissing($required);
			}
		}
		return $this;
	}

}
