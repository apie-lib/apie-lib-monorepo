<?php
namespace Apie\Faker\FileFakers;

use Apie\Core\FileStorage\StoredFile;
use Apie\Faker\Interfaces\ApieFileFaker;
use Faker\Generator;
use Symfony\Component\Finder\Finder;

final class BackgroundFileFaker implements ApieFileFaker
{
    public function createOriginalFilename(Generator $faker): string
    {
        return $faker->word() . '.jpg';
    }
    public function createMimeType(): string
    {
        return 'image/jpeg';
    }
    public static function isSupported(): bool
    {
        return true;
    }

    /** @return resource */
    public function createResource(Generator $faker, string $originalFilename, string $mimeType): mixed
    {
        $serverPath = __DIR__ .  '/../../fixtures/images/backgrounds';
        $files = iterator_to_array(Finder::create()->in($serverPath)->files());

        $file = StoredFile::createFromLocalFile($faker->randomElement($files), $this->createMimeType());
        return $file->getStream()->detach();
    }
}
