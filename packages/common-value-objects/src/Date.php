<?php


namespace Apie\CommonValueObjects;

use Apie\ValueObjects\Exceptions\InvalidValueForValueObjectException;
use Apie\ValueObjects\StringTrait;
use Apie\ValueObjects\ValueObjectInterface;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

class Date implements ValueObjectInterface
{
    private static $format = 'Y-m-d';

    /**
     * @var DateTimeInterface
     */
    private $date;

    public function __construct(string $input)
    {
        $this->date = DateTimeImmutable::createFromFormat(self::$format, $input, new DateTimeZone('UTC'));
        if ($this->date === false) {
            throw new InvalidValueForValueObjectException($input, $this);
        }
    }

    public static function resetFormat(): void
    {
        self::$format = 'Y-m-d';
    }

    public static function getFormat(): string
    {
        return self::$format;
    }

    public static function setFormat(string $format): void
    {
        self::$format = $format;
    }

    public static function fromNative($value)
    {
        if ($value instanceof DateTimeInterface) {
            return new self($value->format(self::getFormat()));
        }
        return new self($value);
    }

    public function toNative()
    {
        return $this->date->format(self::$format);
    }
}