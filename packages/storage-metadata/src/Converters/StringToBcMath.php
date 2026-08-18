<?php
namespace Apie\StorageMetadata\Converters;

use Apie\Core\Utils\ConverterUtils;
use Apie\TypeConverter\ConverterInterface;
use BcMath\Number;
use DateTime;
use DateTimeInterface;
use GMP;
use ReflectionType;

/**
 * @implements ConverterInterface<string, Number>
 */
class StringToBcMath implements ConverterInterface
{
    public function convert(string $input): Number
    {
        return new Number($input);
    }
}
