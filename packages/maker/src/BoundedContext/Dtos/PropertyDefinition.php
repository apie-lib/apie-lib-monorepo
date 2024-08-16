<?php
namespace Apie\Maker\BoundedContext\Dtos;

use Apie\Core\Attributes\FakeMethod;
use Apie\Core\Dto\DtoInterface;
use Apie\Maker\Enums\NullableOption;
use Apie\Maker\Enums\PrimitiveType;
use Apie\Maker\ValueObjects\ClassNameReference;
use Apie\Maker\ValueObjects\PropertyDefinitionName;
use Apie\Maker\ValueObjects\VendorValueObject;
use Faker\Generator;
use Stringable;

#[FakeMethod('createRandom')]
final class PropertyDefinition implements DtoInterface, Stringable
{
    public function __construct(
        public VendorValueObject|PrimitiveType|ClassNameReference $type,
        public PropertyDefinitionName $name,
        public bool $requiredOnConstruction,
        public bool $writable,
        public bool $readable,
        public NullableOption $nullable = NullableOption::AlwaysNull,
    ) {
    }

    public function __toString(): string
    {
        return $this->name->toNative();
    }

    public static function createRandom(Generator $faker): self
    {
        $variableOptions = $faker->randomElement([
            [true, true, true, NullableOption::AlwaysNull],
            [false, true, true, NullableOption::AlwaysNull],
            [true, false, true, NullableOption::AlwaysNull],
            [true, true, true, NullableOption::NeverNullable],
            [true, false, true, NullableOption::NeverNullable],
            [true, true, true, NullableOption::InitialNull],
            [true, false, true, NullableOption::InitialNull],
        ]);
        return new self(
            $faker->fakeClass($faker->randomElement([VendorValueObject::class, PrimitiveType::class])),
            PropertyDefinitionName::createRandom($faker),
            ...$variableOptions
        );
    }
}
