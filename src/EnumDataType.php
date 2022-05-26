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
 */
enum EnumDataType {
	case UNIT;
	case INT;
	case STRING;
}
