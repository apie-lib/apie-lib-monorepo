<?php

namespace Apie\Maker\BoundedContext\Identifiers;

use Apie\Core\Attributes\FakeMethod;
use Apie\Core\Identifiers\Identifier;
use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Core\Identifiers\Ulid;
use Apie\Core\ValueObjects\IsStringWithRegexValueObject;
use Apie\Maker\BoundedContext\Resources\BoundedContextDefinition;
use Faker\Generator;
use ReflectionClass;

#[FakeMethod('createRandom')]
final class BoundedContextDefinitionIdentifier implements IdentifierInterface
{
    use IsStringWithRegexValueObject;

    public static function getRegularExpression(): string
    {
        return '/^[a-z][a-z0-9]*\|[a-zA-Z0-9]{22}$/';
    }

    public static function getReferenceFor(): ReflectionClass
    {
        return new ReflectionClass(BoundedContextDefinition::class);
    }

    public static function fromNameAndUlid(Identifier $name, Ulid $ulid): self
    {
        return new self($name->toNative() . '|' . $ulid->toNative());
    }

    public static function createRandom(Generator $faker): self
    {
        return self::fromNameAndUlid(Identifier::createRandom($faker), Ulid::createRandom());
    }
}
