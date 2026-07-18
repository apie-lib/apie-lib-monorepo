<?php
namespace Apie\Core\Translator\ValueObjects;

use Apie\Core\Attributes\Description;
use Apie\Core\Attributes\ExampleValue;
use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Core\ValueObjects\Interfaces\HasRegexValueObjectInterface;
use Apie\Core\ValueObjects\Utils;
use ReflectionClass;

#[Description('A translation string for an Apie translation following a rigid, predictable structure.')]
#[ExampleValue('apie.bounded.example.resource.user.example.test.singular.authenticated')]
abstract class AbstractTranslation implements HasRegexValueObjectInterface
{
    protected const PREFIX_REGEX = 'apie\.(?:bounded\.([a-z][a-z0-9]*)\.)?(?:resource\.([a-z0-9]+(?:_[a-z0-9]+)*)\.)?';
    /**
     * Override this to define the translation namespace.
     * Must not include prefix or suffix separators.
     */
    protected const MIDDLE_REGEX = '[^.]+(?:\.[^.]+)*';
    protected const SUFFIX_REGEX = '(?:\.(?:singular|plural))?(?:\.(?:authenticated|unauthenticated))?';

    final protected function __construct(
        protected TranslationStringPrefix $prefix,
        protected string $middleSection,
        protected TranslationStringSuffix $suffix
    ) {
        if (!preg_match('/^' . static::MIDDLE_REGEX . '$/', $middleSection)) {
            throw new InvalidStringForValueObjectException(
                $prefix . $middleSection . $suffix,
                new ReflectionClass(static::class)
            );
        }
    }

    public static function getRegularExpression(): string
    {
        return '/^' . self::PREFIX_REGEX . static::MIDDLE_REGEX . self::SUFFIX_REGEX . '$/';
    }

    final public function toNative(): string
    {
        return $this->prefix . $this->middleSection . $this->suffix;
    }

    final public function __toString(): string
    {
        return $this->toNative();
    }

    final public function jsonSerialize(): string
    {
        return $this->toNative();
    }

    final public static function fromNative(mixed $input): static
    {
        $input = Utils::toString($input);
        $prefix = TranslationStringPrefix::createFromTranslation($input);
        $inputSection = substr($input, strlen($prefix));
        $suffix = TranslationStringSuffix::createFromTranslation($inputSection);
    
        $middleSection = substr($inputSection, 0, strlen($inputSection) - strlen($suffix));

        return new static(
            $prefix,
            $middleSection,
            $suffix
        );
    }
}