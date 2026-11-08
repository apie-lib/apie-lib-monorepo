<?php
namespace Apie\RegexValueObjects;

use Apie\Core\Attributes\Description;
use Apie\Core\Attributes\FakeMethod;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;
use Apie\Core\ValueObjects\IsStringValueObject;
use Apie\RegexValueObjects\Exceptions\InvalidPhpRegularExpression;

#[FakeMethod("createRandom")]
#[Description('Any regular expression that can be parsed with PHP preg_match method.')]
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
