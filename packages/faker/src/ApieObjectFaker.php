<?php
namespace Apie\Faker;

use Apie\Faker\Exceptions\ClassCanNotBeFakedException;
use Apie\Faker\Fakers\DateValueObjectFaker;
use Apie\Faker\Fakers\EnumFaker;
use Apie\Faker\Fakers\StringValueObjectWithRegexFaker;
use Apie\Faker\Fakers\TextValueObjectFaker;
use Apie\Faker\Fakers\UseDefaultGeneratorFaker;
use Apie\Faker\Interfaces\ApieClassFaker;
use Faker\Generator;
use Faker\Provider\Base;
use ReflectionClass;

/**
 * This is a stub class
 */
final class ApieObjectFaker extends Base
{
    /**
     * @ApieClassFaker[]
     */
    private array $fakers;

    public function __construct(Generator $generator, ApieClassFaker... $fakers)
    {
        $this->fakers = $fakers;
        parent::__construct($generator);
    }

    public static function createWithDefaultFakers(Generator $generator, ApieClassFaker... $additional): self
    {
        return new self(
            $generator,
            new TextValueObjectFaker(),
            new UseDefaultGeneratorFaker(),
            new DateValueObjectFaker(),
            new StringValueObjectWithRegexFaker(),
            new EnumFaker(),
            ...$additional
        );
    }

    /**
     * @template T of object
     * @param class-string<T>
     * @return T
     */
    public function fakeClass(string $className): object
    {
        $refl = new ReflectionClass($className);
        foreach ($this->fakers as $faker) {
            if ($faker->supports($refl)) {
                return $faker->fakeFor($this->generator, $refl);
            }
        }

        throw new ClassCanNotBeFakedException($refl);
    }
}
