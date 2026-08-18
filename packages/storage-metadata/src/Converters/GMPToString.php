<?php
namespace Apie\StorageMetadata\Converters;

use Apie\Core\Utils\ConverterUtils;
use Apie\TypeConverter\ConverterInterface;
use DateTime;
use DateTimeInterface;
use GMP;
use ReflectionType;

/**
 * @implements ConverterInterface<GMP, string>
 */
class GMPToString implements ConverterInterface
{
    public function convert(GMP $input): string
    {
        return gmp_strval($input);
    }
}
