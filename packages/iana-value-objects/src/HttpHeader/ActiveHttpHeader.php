<?php
namespace Apie\IanaValueObjects\HttpHeader;

use Apie\Core\Lists\StringSet;
use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Core\ValueObjects\Interfaces\LimitedOptionsInterface;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;
use Apie\IanaValueObjects\HasActiveFilter;
use Apie\IanaValueObjects\StaticDataValueObject;

/**
 * All message headers (HTTP fields) registered in the IANA HTTP Field Names Registry.
 *
 * @see https://www.iana.org/assignments/http-fields/http-fields.xhtml
 *
 * Only active HTTP headers can be used (e.g. permanent or provisional, not deprecated/obsoleted).
 */
final class ActiveHttpHeader implements StringValueObjectInterface, LimitedOptionsInterface
{
    use IsHttpHeader, StaticDataValueObject {
        IsHttpHeader::convert insteadof StaticDataValueObject;
        validate as private validLanguage;
    }
    use HasActiveFilter;

    protected static function requiresActive(): bool
    {
        return true;
    }

    protected static function getActiveData(): array
    {
        return array_filter(static::getData(), function (array $data) {
            $status = $data['Status'] ?? null;
            return $status === 'permanent';
        });
    }

    public static function getOptions(): StringSet
    {
        return new StringSet(array_keys(static::getActiveData()));
    }

    public static function validate(string $input): void
    {
        $inputLower = strtolower($input);
        $data = static::getData()[$inputLower] ?? null;
        $status = $data['Status'] ?? null;
        if ($status !== 'permanent') {
            throw new InvalidStringForValueObjectException(
                $input,
                (new \ReflectionClass(static::class))
            );
        }

        static::validLanguage($input);
    }
}
