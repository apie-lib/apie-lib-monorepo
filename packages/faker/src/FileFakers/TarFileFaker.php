<?php
namespace Apie\Faker\FileFakers;

use Apie\Faker\Interfaces\ApieFileFaker;
use Apie\Faker\SeededFile;
use Faker\Generator;

/**
 * Generates a ustar tar archive filled with random fake files, similar to the zip faker.
 */
final class TarFileFaker implements ApieFileFaker
{
    private static bool $alreadyCreating = false;

    public function createOriginalFilename(Generator $faker): string
    {
        return $faker->word() . '.tar';
    }

    public function createMimeType(): string
    {
        return 'application/x-tar';
    }

    public static function isSupported(): bool
    {
        return !self::$alreadyCreating;
    }

    /** @return resource */
    public function createResource(Generator $faker, string $originalFilename, string $mimeType): mixed
    {
        $resource = tmpfile();
        self::$alreadyCreating = true;
        try {
            for ($i = $faker->numberBetween(1, 10); $i > 0; $i--) {
                /** @var SeededFile $file */
                $file = $faker->fakeClass(SeededFile::class);
                $directory = $faker->boolean(30) ? $faker->word() . '/' : '';
                fwrite($resource, $this->createEntry(
                    $directory . $file->getClientFilename(),
                    (string) $file->getStream(),
                    $faker->dateTimeBetween('-2 years')->getTimestamp()
                ));
            }
        } finally {
            self::$alreadyCreating = false;
        }
        // end of archive is marked by 2 empty blocks.
        fwrite($resource, str_repeat("\0", 1024));
        rewind($resource);

        return $resource;
    }

    private function createEntry(string $name, string $data, int $modificationTime): string
    {
        $size = strlen($data);
        $header = str_pad(substr($name, 0, 100), 100, "\0")
            . sprintf('%07o', 0644) . "\0"
            . sprintf('%07o', 1000) . "\0"
            . sprintf('%07o', 1000) . "\0"
            . sprintf('%011o', $size) . "\0"
            . sprintf('%011o', $modificationTime) . "\0"
            . '        '
            . '0'
            . str_repeat("\0", 100)
            . "ustar\0" . '00'
            . str_pad('apie', 32, "\0")
            . str_pad('apie', 32, "\0")
            . str_repeat("\0", 8 + 8 + 155 + 12);
        // the checksum is calculated while the checksum field itself contains spaces.
        $checksum = sprintf('%06o', array_sum(unpack('C*', $header)) ?: 0) . "\0 ";
        $header = substr_replace($header, $checksum, 148, 8);

        return $header . str_pad($data, (int) (ceil($size / 512) * 512), "\0");
    }
}
