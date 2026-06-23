<?php
namespace Apie\IanaValueObjects;

use Apie\Core\Lists\StringSet;
use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Core\ValueObjects\Interfaces\LimitedOptionsInterface;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;

/**
 * All message headers (HTTP fields) registered in the IANA HTTP Field Names Registry.
 *
 * @see https://www.iana.org/assignments/http-fields/http-fields.xhtml
 *
 * Only active HTTP headers can be used (e.g. permanent or provisional, not deprecated/obsoleted).
 */
final class ActiveHttpHeader implements StringValueObjectInterface, LimitedOptionsInterface
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
        return require __DIR__ . '/../fixtures/http-fields.php';
    }

    public static function getOptions(): StringSet
    {
        return new StringSet(array_keys(static::getActiveData()));
    }

    protected function convert(string $input): string
    {
        $inputLower = strtolower($input);
        $data = static::getData();
        if (!isset($data[$inputLower])) {
            return $input;
        }

        return $data[$inputLower]['Field Name'] ?? $input;
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
