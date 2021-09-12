<?php

namespace Walnut\Lib\DataType;

use Attribute;
use Walnut\Lib\DataType\Exception\ArrayType\{
	ArrayElementsNotUnique, TooFewArrayElements, TooManyArrayElements
};
use Walnut\Lib\DataType\Exception\{InvalidValueRange, InvalidValueType};

/**
 * @package Walnut\Lib\DataType
 * @readonly
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class ArrayData implements ValueValidator {
	public function __construct(
		public /*readonly*/ bool $nullable = false,
		public /*readonly*/ ?int $minItems = null,
		public /*readonly*/ ?int $maxItems = null,
		public /*readonly*/ bool $uniqueItems = false
	) {
		if (isset($this->minItems, $this->maxItems) && $this->minItems > $this->maxItems) {
			throw new InvalidValueRange($this->minItems, $this->maxItems);
		}
	}

	/**
	 * @param mixed $value
	 * @throws InvalidValueType
	 * @throws TooFewArrayElements
	 * @throws TooManyArrayElements
	 * @throws ArrayElementsNotUnique
	 */
	public function validateValue(mixed $value): void {
		if (!is_array($value) && !($value === null && $this->nullable)) {
			throw new InvalidValueType('array', gettype($value));
		}
		if (isset($value)) {
			$l = count($value);
			$this->tooFew($l)->tooMany($l)->notUnique($l, $value);
		}
	}

	private function tooFew(int $l): self {
		if (isset($this->minItems) && $l < $this->minItems) {
			throw new TooFewArrayElements($this->minItems, $l);
		}
		return $this;
	}

	private function tooMany(int $l): self {
		if (isset($this->maxItems) && $l > $this->maxItems) {
			throw new TooManyArrayElements($this->maxItems, $l);
		}
		return $this;
	}

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
