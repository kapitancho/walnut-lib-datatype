<?php

namespace Walnut\Lib\DataType;

use Walnut\Lib\DataType\Exception\DataImporterException;
use Walnut\Lib\DataType\Exception\InvalidData;

interface DataImporter {
	/**
	 * @template T
	 * @param object $object
	 * @param class-string<T> $className
	 * @return T
	 * @throws DataImporterException|InvalidData
	 */
	public function import(object $object, string $className): object;

	/**
	 * @param object $object
	 * @throws DataImporterException|InvalidData
	 */
	public function validate(object $object): void;

}