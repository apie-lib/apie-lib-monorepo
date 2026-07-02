<?php
namespace Apie\IanaValueObjects\UriScheme;

use Apie\Core\Lists\StringSet;
use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Core\ValueObjects\Interfaces\LimitedOptionsInterface;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;
use Apie\IanaValueObjects\HasActiveFilter;

/**
 * All URI schemes registered in the IANA URI Schemes Registry.
 *
 * @see https://www.iana.org/assignments/uri-schemes/uri-schemes.xhtml
 *
 * Only active URI schemes can be used (e.g. Permanent or Provisional, not Historical).
 */
final class ActiveUriScheme implements StringValueObjectInterface, LimitedOptionsInterface
{
    use IsUriScheme {
        validate as private validLanguage;
    }
    use HasActiveFilter;

    protected static function getActiveData(): array
    {
        return array_filter(static::getData(), function (array $data) {
            $status = $data['Status'] ?? null;
            return in_array($status, ['Permanent', 'Provisional']);
        });
    }

    public static function validate(string $input): void
    {
        $data = static::getData()[$input] ?? null;
        $status = $data['Status'] ?? null;
        if ($status !== 'Permanent' && $status !== 'Provisional') {
            throw new InvalidStringForValueObjectException(
                $input,
                (new \ReflectionClass(static::class))
            );
        }

        static::validLanguage($input);
    }
}
