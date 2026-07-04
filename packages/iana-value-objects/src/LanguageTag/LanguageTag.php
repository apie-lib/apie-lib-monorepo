<?php
namespace Apie\IanaValueObjects\LanguageTag;

use Apie\Core\Identifiers\Identifier;
use Apie\Core\Lists\IdentifierList;
use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;
use Apie\Core\ValueObjects\IsStringValueObject;
use Apie\Core\ValueObjects\SingleLetter;
use Apie\Core\Attributes\FakeMethod;
use Faker\Factory;
use Faker\Generator;

#[FakeMethod('createRandom')]
class LanguageTag implements StringValueObjectInterface
{
    use IsStringValueObject;

    private static array $resolvedLanguageSubtags = [];

    public static function validate(string $input): void
    {
        if (!isset(self::$resolvedLanguageSubtags[$input])) {
            $split = explode('-', $input);
            $language = [Language::fromNative(array_shift($split))];   
            $classes = [
                    LanguageExtlang::class,
                    LanguageScript::class,
                    LanguageRegion::class,
                    LanguageVariant::class,
                ];
            while (!empty($split)) {
                $subtag = array_shift($split);
                
                while (!empty($classes)) {
                    $class = array_shift($classes);
                    try {
                        $language[] = $class::fromNative($subtag);
                        if (in_array($class, [LanguageExtlang::class, LanguageVariant::class])) {
                            array_unshift($classes, $class);
                        }
                        continue 2;
                    } catch (\Throwable) {
                        // ignore
                    }
                }

                // parse the extensions
                try {
                    $singleton = SingleLetter::fromNative($subtag);
                    $extensions = [];

                    while (!empty($split)) {
                        $subtag = array_shift($split);
                        try {
                            if (strlen($subtag) === 1) {
                                array_unshift($split, $subtag);
                                
                                break;
                            }
                            $extensions[] = Identifier::fromNative($subtag);
                        } catch (\Throwable $err) {
                            throw new InvalidStringForValueObjectException(
                                $input,
                                (new \ReflectionClass(static::class)),
                                $err
                            );
                        }
                    }
                    $language[] = new LanguageExtension($singleton, new IdentifierList($extensions));
                    break;
                } catch (\Throwable) {
                    // ignore
                }

                throw new InvalidStringForValueObjectException(
                    $input,
                    (new \ReflectionClass(static::class))
                );
            }
            self::$resolvedLanguageSubtags[$input] = $language;
        }
    }

    public static function createRandom(Generator $factory): self
    {
        return new LanguageTag($factory->languageCode());
    }
}