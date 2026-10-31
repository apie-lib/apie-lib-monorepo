<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Actions;

use Apie\Maker\ValueObjects\ClassNameReference;
use Apie\Maker\ValueObjects\VendorValueObject;
use Faker\Generator;

class FakerTest
{
    public function __construct(
        private readonly Generator $faker
    ) {
    }
    public function fake(
        VendorValueObject|ClassNameReference $className
    ): mixed {
        return $this->faker->fakeClass($className->toNative());
    }
}
