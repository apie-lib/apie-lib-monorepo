<?php
namespace Apie\TypescriptCodeBuilder\ValueObjects;

use Apie\Core\Attributes\Description;
use Apie\Core\Attributes\FakeMethod;
use Apie\Core\Identifiers\KebabCaseSlug;
use Apie\Core\Utils\IdentifierConstants;
use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Core\ValueObjects\Interfaces\HasRegexValueObjectInterface;
use Apie\Core\ValueObjects\IsStringWithRegexValueObject;
use Faker\Generator;
use ReflectionClass;

#[FakeMethod('createRandom')]
#[Description('A valid JavaScript identifier for keys of a javascript object')]
class JavascriptIdentifierKey implements HasRegexValueObjectInterface
{
    use IsStringWithRegexValueObject;

    public function toCode()
    {
        try {
            $id = JavascriptIdentifier::fromNative($this->internal);
            return $id->toNative();
        } catch (InvalidStringForValueObjectException) {
            return json_encode($this->internal);
        }
    }

    public static function getRegularExpression(): string
    {
        return '/^[A-Za-z_][A-Za-z0-9_]*$/D';
    }

    public static function validate(string $input): void
    {
        if (!preg_match(static::getRegularExpression(), $input)) {
            throw new InvalidStringForValueObjectException($input, new ReflectionClass(self::class));
        }
    }

    public static function createFromText(string $input): static
    {
        if (!preg_match(static::getRegularExpression(), $input)) {
            $input = KebabCaseSlug::fromText($input)->toCamelCaseSlug()->toNative();
        }

        return new self($input);
    }

    public static function createRandom(Generator $faker): static
    {
        $input = $faker->randomElement(IdentifierConstants::RANDOM_IDENTIFIERS);

        return new static($input);
    }

    public function humanize(): string
    {
        return $this->toNative();
    }
}
