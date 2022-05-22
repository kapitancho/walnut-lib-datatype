<?php

namespace Walnut\Lib\DataType;

use Attribute;
use Walnut\Lib\DataType\Exception\{InvalidValueRange};
use Walnut\Lib\DataType\Exception\ArrayType\{TooFewArrayElements};
use Walnut\Lib\DataType\Exception\ArrayType\ArrayElementsNotUnique;
use Walnut\Lib\DataType\Exception\ArrayType\TooManyArrayElements;
use Walnut\Lib\DataType\Exception\InvalidValueType;

/**
 * @package Walnut\Lib\DataType
 * @readonly
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class ArrayData implements CompositeValue {
	public function __construct(
		public readonly bool $nullable = false,
		public readonly ?int $minItems = null,
		public readonly ?int $maxItems = null,
		public readonly bool $uniqueItems = false,
		public readonly DirectValue|CompositeValue|ClassRef $items = new AnyData
	) {
		if (isset($this->minItems, $this->maxItems) && $this->minItems > $this->maxItems) {
			throw new InvalidValueRange($this->minItems, $this->maxItems);
		}
	}

	/**
	 * @param null|string|float|int|bool|array|object $value
	 * @param CompositeValueHydrator $nestedValueHydrator
	 * @return null|array<int, int|float|bool|string|array|object|null>
	 * @throws InvalidValueType|TooFewArrayElements|TooManyArrayElements|ArrayElementsNotUnique
	 */
	public function importValue(
		null|string|float|int|bool|array|object $value,
		CompositeValueHydrator $nestedValueHydrator
	): ?array {
		if (!is_array($value) && !($value === null && $this->nullable)) {
			throw new InvalidValueType('array', gettype($value));
		}
		if (!isset($value)) {
			return null;
		}
		$l = count($value);
		$this->tooFew($l)->tooMany($l)->notUnique($l, $value);
		if (!array_is_list($value)) {
			throw new InvalidValueType('array', 'dictionary');
		}
		/**
		 * @var array<int, int|float|bool|string|array|object|null> $result
		 */
		$result = [];

		/**
		 * @var int $key
		 * @var list<int|float|bool|string|array|object|null> $value
		 */
		foreach($value as $key => $val) {
			/**
			 * @var null|string|float|int|bool|array|object $val
			 */
			$result[$key] = $nestedValueHydrator->importNestedValue($val, $this->items, $key);
		}
		return $result;
	}

	/**
	 * @throws TooFewArrayElements
	 */
	private function tooFew(int $l): self {
		if (isset($this->minItems) && $l < $this->minItems) {
			throw new TooFewArrayElements($this->minItems, $l);
		}
		return $this;
	}

	/**
	 * @throws TooManyArrayElements
	 */
	private function tooMany(int $l): self {
		if (isset($this->maxItems) && $l > $this->maxItems) {
			throw new TooManyArrayElements($this->maxItems, $l);
		}
		return $this;
	}

	/**
	 * @throws ArrayElementsNotUnique
	 */
	private function notUnique(int $l, array $value): self {
		if ($this->uniqueItems) {
			$uniqueCount = count(array_unique($value));
			if ($l > $uniqueCount) {
				throw new ArrayElementsNotUnique($l - $uniqueCount);
			}
		}
		return $this;
	}

}
