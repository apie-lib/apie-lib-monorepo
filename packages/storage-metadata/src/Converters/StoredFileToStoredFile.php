<?php
namespace Apie\StorageMetadata\Converters;

use Apie\Core\FileStorage\StoredFile;
use Apie\Core\Utils\ConverterUtils;
use Apie\TypeConverter\ConverterInterface;
use ReflectionType;

/**
 * @implements ConverterInterface<StoredFile, StoredFile>
 */
class StoredFileToStoredFile implements ConverterInterface
{
    public function convert(StoredFile $input, ?ReflectionType $wantedType): StoredFile
    {
        $class = ConverterUtils::toReflectionClass($wantedType);
        $className = $class?->name;
        //assert($className === null || is_a($className, StoredFile::class, true));
        return match ($className) {
            null, StoredFile::class => $input,
            default => $className::createFromUploadedFile($input, $input->getStoragePath())
        };
    }
}