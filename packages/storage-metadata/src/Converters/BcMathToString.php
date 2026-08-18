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
 * @implements ConverterInterface<Number, string>
 */
class BcMathToString implements ConverterInterface
{
    public function convert(Number $input): string
    {
        return $input->__toString();
    }
}
