<?php

namespace Walnut\Lib\DataType\Exception;

use RuntimeException;

final class InvalidData extends RuntimeException {
	private const ERROR_MESSAGE = "Error in value '%s': '%s'";
	public function __construct(
		public /*readonly*/ string $path,
		public /*readonly*/ mixed $value,
		public /*readonly*/ InvalidValue $invalidValue
	) {
		$this->path = ltrim($this->path, '.');
		$message = sprintf(self::ERROR_MESSAGE, $this->path, (string)$this->invalidValue);
		$code = 0;
		parent::__construct(
			$message,
			$code,
			$this->invalidValue
		);
	}
}