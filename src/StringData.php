<?php

namespace Walnut\Lib\DataType;

use Attribute;
use Walnut\Lib\DataType\Exception\{InvalidValueRange, InvalidValueType};
use Walnut\Lib\DataType\Exception\StringType\{
	StringIncorrectlyFormatted, StringNotInEnum, StringTooLong, StringTooShort
};

/**
 * @package Walnut\Lib\DataType
 * @readonly
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class StringData implements ValueValidator {
	public const DATE_TIME_REGEXP = '#^(\d+)-(0[1-9]|1[012])-(0[1-9]|[12]\d|3[01])T([01]\d|2[0-3]):([0-5]\d):([0-5]\d|60)(\.\d+)?(([Zz])|([\+|\-]([01]\d|2[0-3])))$#';
	public const FORMAT_DATE_TIME = 'date-time';

	/**
	 * @param bool $nullable
	 * @param int|null $minLength
	 * @param int|null $maxLength
	 * @param string|null $format
	 * @param string|null $pattern
	 * @param string[]|null $enum
	 */
	public function __construct(
		public readonly bool $nullable = false,
		public readonly ?int $minLength = null,
		public readonly ?int $maxLength = null,
		public readonly ?string $format = null,
		public readonly ?string $pattern = null,
		public readonly ?array $enum = null
	) {
		if (isset($this->minLength, $this->maxLength) && $this->minLength > $this->maxLength) {
			throw new InvalidValueRange($this->minLength, $this->maxLength);
		}
	}

	/**
	 * @param mixed $value
	 * @throws InvalidValueType
	 * @throws StringTooShort
	 * @throws StringTooLong
	 * @throws StringIncorrectlyFormatted
	 * @throws StringNotInEnum
	 */
	public function validateValue(mixed $value): void {
		if (!is_string($value) && !($value === null && $this->nullable)) {
			throw new InvalidValueType('string', gettype($value));
		}
		if (isset($value)) {
			$l = mb_strlen($value);
			$this->tooShort($l)->tooLong($l)->notInEnum($value)->wrongFormat($value);
		}
	}

	private function tooShort(int $l): self {
		if (isset($this->minLength) && $l < $this->minLength) {
			throw new StringTooShort($this->minLength, $l);
		}
		return $this;
	}

	private function tooLong(int $l): self {
		if (isset($this->maxLength) && $l > $this->maxLength) {
			throw new StringTooLong($this->maxLength, $l);
		}
		return $this;
	}

	private function notInEnum(string $value): self {
		if (isset($this->enum) && !in_array($value, $this->enum, true)) {
			throw new StringNotInEnum($this->enum, $value);
		}
		return $this;
	}

	private function wrongFormat(string $value): self {
		if (isset($this->format)) {
			switch ($this->format) {
				case self::FORMAT_DATE_TIME:
					if (!preg_match(self::DATE_TIME_REGEXP, $value)) {
						throw new StringIncorrectlyFormatted($this->format, $value);
					}
				break;
			}
		}
		return $this;
	}

}
