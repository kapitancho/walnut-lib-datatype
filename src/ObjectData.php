<?php

namespace Walnut\Lib\DataType;

use Attribute;
use Walnut\Lib\DataType\Exception\{InvalidValueRange, InvalidValueType};
use Walnut\Lib\DataType\Exception\ObjectType\{
	RequiredObjectPropertyMissing, TooFewObjectProperties, TooManyObjectProperties
};

/**
 * @package Walnut\Lib\DataType
 * @readonly
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
final class ObjectData implements ValueValidator {
	/**
	 * ObjectValue constructor.
	 * @param bool $nullable
	 * @param string[] $required
	 * @param int|null $minProperties
	 * @param int|null $maxProperties
	 * @param string|null $additionalPropertiesIn
	 */
	public function __construct(
		public readonly bool $nullable = false,
		public readonly array $required = [],
		public readonly ?int $minProperties = null,
		public readonly ?int $maxProperties = null,
		public readonly ?string $additionalPropertiesIn = null
	) { 
		if (isset($this->minProperties, $this->maxProperties) && $this->minProperties > $this->maxProperties) {
			throw new InvalidValueRange($this->minProperties, $this->maxProperties);
		}		
	}

	/**
	 * @param mixed $value
	 * @throws InvalidValueType
	 * @throws RequiredObjectPropertyMissing
	 * @throws TooFewObjectProperties
	 * @throws TooManyObjectProperties
	 */
	public function validateValue(mixed $value): void {
		if (!is_object($value) && !($value === null && $this->nullable)) {
			throw new InvalidValueType('object', gettype($value));
		}
		if (isset($value)) {
			$l = count(get_object_vars($value));
			$this->tooFew($l)->tooMany($l)->requiredMissing($value);
		}
	}

	private function tooFew(int $l): self {
		if (isset($this->minProperties) && $l < $this->minProperties) {
			throw new TooFewObjectProperties($this->minProperties, $l);
		}
		return $this;
	}

	private function tooMany(int $l): self {
		if (isset($this->maxProperties) && $l > $this->maxProperties) {
			throw new TooManyObjectProperties($this->maxProperties, $l);
		}
		return $this;
	}

	private function requiredMissing(object $value): self {
		foreach($this->required as $required) {
			if (!property_exists($value, $required)) {
				throw new RequiredObjectPropertyMissing($required);
			}
		}
		return $this;
	}

}
