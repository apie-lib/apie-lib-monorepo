<?php
namespace Apie\Faker\FileFakers;

use Apie\Faker\Interfaces\ApieFileFaker;
use Apie\Faker\SeededFile;
use Faker\Generator;
use Nyholm\Psr7\Stream;

/**
 * Generates a gzip compressed random fake file.
 */
final class GzFileFaker implements ApieFileFaker
{
    private static bool $alreadyCreating = false;

    /** @var array{string, string}|null */
    private ?array $inner = null;

    public function createOriginalFilename(Generator $faker): string
    {
        return $this->createInner($faker)[0] . '.gz';
    }

    public function createMimeType(): string
    {
        return 'application/gzip';
    }

    public static function isSupported(): bool
    {
        return !self::$alreadyCreating && function_exists('gzencode');
    }

    /** @return resource */
    public function createResource(Generator $faker, string $originalFilename, string $mimeType): mixed
    {
        [$name, $contents] = $this->inner ?? $this->createInner($faker);
        $this->inner = null;
        $compressed = (string) gzencode($contents, $faker->numberBetween(1, 9));
        // set the FNAME flag so gunzip -N restores the original name with extension.
        $compressed = substr($compressed, 0, 3) . chr(ord($compressed[3]) | 8) . substr($compressed, 4, 6)
            . $name . "\0" . substr($compressed, 10);

        return Stream::create($compressed)->detach();
    }

    /** @return array{string, string} */
    private function createInner(Generator $faker): array
    {
        if ($this->inner !== null) {
            return $this->inner;
        }
        self::$alreadyCreating = true;
        try {
            /** @var SeededFile $file */
            $file = $faker->fakeClass(SeededFile::class);
            $name = basename((string) $file->getClientFilename());

            return $this->inner = [$name, (string) $file->getStream()];
        } finally {
            self::$alreadyCreating = false;
        }
    }
}
