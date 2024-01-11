<?php
namespace Apie\RegexValueObjects;

use Apie\Core\Attributes\FakeMethod;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;
use Apie\Core\ValueObjects\IsStringValueObject;
use Apie\RegexValueObjects\Exceptions\InvalidPhpRegularExpression;

#[FakeMethod("createRandom")]
final class PhpRegularExpression implements StringValueObjectInterface
{
    use IsStringValueObject;
    use SharedRegularExpression;

    public static function validate(string $input): void
    {
        if (false === @preg_match($input, '')) {
            throw new InvalidPhpRegularExpression($input, preg_last_error_msg());
        }
    }
}
