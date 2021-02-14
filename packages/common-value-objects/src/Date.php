<?php


namespace Apie\CommonValueObjects;

use Apie\Core\Interfaces\HasSchemaInformationContract;
use Apie\OpenapiSchema\Contract\SchemaContract;
use Apie\OpenapiSchema\Factories\SchemaFactory;
use Apie\ValueObjects\Exceptions\InvalidValueForValueObjectException;
use Apie\ValueObjects\ValueObjectInterface;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

class Date implements ValueObjectInterface, HasSchemaInformationContract
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

    public static function toSchema(): SchemaContract
    {
        return SchemaFactory::createStringSchema('date')
            ->with('example', (new DateTime('1984-01-28'))->format(self::getFormat()));
    }
}
