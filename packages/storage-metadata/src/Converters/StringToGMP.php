<?php
namespace Apie\StorageMetadata\Converters;

use Apie\Core\Utils\ConverterUtils;
use Apie\TypeConverter\ConverterInterface;
use DateTime;
use DateTimeInterface;
use GMP;
use ReflectionType;

/**
 * @implements ConverterInterface<string, GMP>
 */
class StringToGMP implements ConverterInterface
{
    public function convert(string $input): GMP
    {
        return new GMP($input);
    }
}
