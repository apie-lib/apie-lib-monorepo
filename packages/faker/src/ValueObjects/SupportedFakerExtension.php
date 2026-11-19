<?php

namespace Apie\Faker\ValueObjects;

use Apie\Core\Attributes\Description;
use Apie\Core\Lists\StringSet;
use Apie\Core\ValueObjects\Interfaces\HasRegexValueObjectInterface;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;
use Apie\Core\ValueObjects\Interfaces\LimitedOptionsInterface;
use Apie\Core\ValueObjects\IsStringWithRegexValueObject;
    use Apie\Faker\ApieObjectFaker;
    use Faker\Factory;

use Apie\Faker\FileFakerFactory;
use Faker\Generator;

#[Description('A file extension that is supported by the faker library')]
class SupportedFakerExtension implements LimitedOptionsInterface, StringValueObjectInterface, HasRegexValueObjectInterface
{

    private static function createFaker(): Generator
    {
        $faker = Factory::create();
        $faker->addProvider(ApieObjectFaker::createWithDefaultFakers($faker));
        return $faker;
    }

    use IsStringWithRegexValueObject;

    /**
     * @return list<string>
     */
    private static function getSupportedExtensions(): array
    {
        $extensions = [];
        $faker = self::createFaker();
        foreach (FileFakerFactory::getSupportedFileFakers() as $fileFaker) {
            $extensions[] = pathinfo($fileFaker->createOriginalFilename($faker), PATHINFO_EXTENSION);
        }
        sort($extensions);

        return $extensions;
    }

    public static function getOptions(): StringSet
    {
        return new StringSet(self::getSupportedExtensions());
    }

    public static function getRegularExpression(): string
    {
        return '/^' . implode('|', self::getSupportedExtensions()) . '$/';
    }
}
