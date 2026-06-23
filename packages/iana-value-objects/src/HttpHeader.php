<?php
namespace Apie\IanaValueObjects;

use Apie\Core\Lists\StringSet;
use Apie\Core\ValueObjects\Interfaces\LimitedOptionsInterface;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;

/**
 * All message headers (HTTP fields) registered in the IANA HTTP Field Names Registry.
 *
 * @see https://www.iana.org/assignments/http-fields/http-fields.xhtml
 *
 * Any header that is not active/valid anymore (e.g. deprecated/obsoleted) can be used as well (for data integrity).
 */
final class HttpHeader implements StringValueObjectInterface, LimitedOptionsInterface
{
    use StaticDataValueObject;

    protected static function requiresActive(): bool
    {
        return false;
    }

    protected static function getData(): array
    {
        return require __DIR__ . '/../fixtures/http-fields.php';
    }

    public static function getOptions(): StringSet
    {
        return new StringSet(array_keys(static::getData()));
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
}
