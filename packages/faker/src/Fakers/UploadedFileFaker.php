<?php
namespace Apie\Faker\Fakers;

use Apie\Core\FileStorage\StoredFile;
use Apie\Faker\Interfaces\ApieClassFaker;
use Apie\Faker\Interfaces\ApieFileFaker;
use Apie\Faker\SeededFile;
use Faker\Generator;
use Psr\Http\Message\UploadedFileInterface;
use ReflectionClass;

/** @implements ApieClassFaker<StoredFile> */
class UploadedFileFaker implements ApieClassFaker
{
    public function supports(ReflectionClass $class): bool
    {
        return UploadedFileInterface::class === $class->name
            || in_array(UploadedFileInterface::class, $class->getInterfaceNames());
    }

    public function fakeFor(Generator $generator, ReflectionClass $class): UploadedFileInterface
    {
        if ($class->name === SeededFile::class) {
            return SeededFile::create(
                $generator,
                $generator->fakeClass(ApieFileFaker::class)
            );
        }
        $internal = $generator->fakeClass(SeededFile::class);
        $className = $class->name === UploadedFileInterface::class ? StoredFile::class : $class->name;
        return $className::createFromUploadedFile($internal);
    }
}
