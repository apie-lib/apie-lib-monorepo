<?php
namespace Apie\Maker\ValueObjects;

use Apie\Core\Attributes\FakeMethod;
use Apie\Core\FileStorage\StoredFile;
use Apie\Core\Lists\StringList;
use Apie\Core\Lists\StringSet;
use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Core\ValueObjects\Interfaces\HasRegexValueObjectInterface;
use Apie\Core\ValueObjects\Interfaces\LimitedOptionsInterface;
use Apie\Core\ValueObjects\IsStringValueObject;
use Apie\Maker\Concerns\IsClassNameReference;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Faker\Generator;
use Psr\Http\Message\UploadedFileInterface;
use ReflectionClass;

#[FakeMethod('createRandom')]
class ClassNameReference implements HasRegexValueObjectInterface, LimitedOptionsInterface
{
    use IsClassNameReference;
    use IsStringValueObject;

    private const CLASSNAMES = [
        StringList::class,
        __CLASS__,
        VendorValueObject::class,
        UploadedFileInterface::class,
        StoredFile::class,
        DateTimeInterface::class,
        DateTimeImmutable::class,
        DateTime::class,
        DateTimeZone::class,
    ];

    public static function validate(string $input): void
    {
        if (!preg_match(static::getRegularExpression(), $input)) {
            throw new InvalidStringForValueObjectException(
                $input,
                new ReflectionClass(self::class)
            );
        }
        if (!class_exists($input) && !interface_exists($input)) {
            throw new InvalidStringForValueObjectException(
                $input,
                new ReflectionClass(self::class)
            );
        }
    }

    public static function createRandom(Generator $factory): self
    {
        return new self($factory->randomElement(self::CLASSNAMES));
    }

    public static function getOptions(): StringSet
    {
        return new StringSet(self::CLASSNAMES);
    }
}
