<?php
namespace Apie\TypescriptCodeBuilder\ValueObjects;

use Apie\Core\Attributes\Description;
use Apie\Core\Attributes\FakeMethod;
use Apie\Core\Utils\IdentifierConstants;
use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Core\ValueObjects\Interfaces\HasRegexValueObjectInterface;
use Apie\Core\ValueObjects\IsStringWithRegexValueObject;
use Faker\Generator;
use ReflectionClass;

#[FakeMethod('createRandom')]
#[Description('A valid JavaScript identifier that is not a reserved keyword')]
class JavascriptIdentifier implements HasRegexValueObjectInterface
{
    use IsStringWithRegexValueObject;

    private const RESERVED_WORDS = [
        'as', 'assert', 'async', 'await', 'break', 'case', 'catch', 'class', 'const',
        'continue', 'debugger', 'default', 'delete', 'do', 'else', 'export', 'extends',
        'false', 'finally', 'for', 'from', 'function', 'get', 'if', 'implements', 'import',
        'in', 'instanceof', 'interface', 'let', 'new', 'null', 'of', 'package', 'private',
        'protected', 'public', 'return', 'set', 'static', 'super', 'switch', 'this', 'throw',
        'true', 'try', 'typeof', 'var', 'void', 'while', 'with', 'yield',
        'enum', 'eval', 'arguments', 'abstract', 'boolean', 'byte', 'char', 'double', 'final',
        'float', 'goto', 'int', 'long', 'native', 'short', 'synchronized', 'throws',
        'transient', 'volatile',
    ];

    public static function getRegularExpression(): string
    {
        return '/^[A-Za-z_][A-Za-z0-9_]*$/D';
    }

    public static function validate(string $input): void
    {
        if (!preg_match(static::getRegularExpression(), $input) || in_array($input, self::RESERVED_WORDS, true)) {
            throw new InvalidStringForValueObjectException($input, new ReflectionClass(self::class));
        }
    }

    public static function createRandom(Generator $faker): static
    {
        do {
            $input = $faker->randomElement(IdentifierConstants::RANDOM_IDENTIFIERS);
        } while (in_array($input, self::RESERVED_WORDS, true));

        return new static($input);
    }

    public function humanize(): string
    {
        return $this->toNative();
    }
}