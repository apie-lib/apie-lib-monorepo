<?php
namespace Apie\IanaValueObjects;

use Apie\Core\Lists\StringSet;
use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Core\ValueObjects\Interfaces\LimitedOptionsInterface;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;

/**
 * All URI schemes registered in the IANA URI Schemes Registry.
 *
 * @see https://www.iana.org/assignments/uri-schemes/uri-schemes.xhtml
 *
 * Only active URI schemes can be used (e.g. Permanent or Provisional, not Historical).
 */
final class ActiveUriScheme implements StringValueObjectInterface, LimitedOptionsInterface
{
    use StaticDataValueObject {
        validate as private validLanguage;
    }

    protected static function requiresActive(): bool
    {
        return true;
    }

    protected static function getData(): array
    {
        return require __DIR__ . '/../fixtures/uri-schemes.php';
    }

    public static function getOptions(): StringSet
    {
        return new StringSet(array_keys(static::getActiveData()));
    }

    public static function validate(string $input): void
    {
        $data = static::getData()[$input] ?? null;
        $status = $data['Status'] ?? null;
        if ($status !== 'Permanent') {
            throw new InvalidStringForValueObjectException(
                $input,
                (new \ReflectionClass(static::class))
            );
        }

        static::validLanguage($input);
    }
}
