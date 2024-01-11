<?php
namespace Apie\RegexValueObjects;

use Apie\Core\Attributes\FakeMethod;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;
use Apie\Core\ValueObjects\IsStringValueObject;
use Apie\RegexValueObjects\Exceptions\ExpressionContainsLookAheads;
use Apie\RegexValueObjects\Exceptions\ExpressionContainsRepeatsInRepeats;
use Apie\RegexValueObjects\Exceptions\InvalidPhpRegularExpression;

#[FakeMethod("createRandom")]
final class PhpSafeRegularExpression implements StringValueObjectInterface
{
    use IsStringValueObject;
    use SharedRegularExpression;

    public static function validate(string $input): void
    {
        if (false === @preg_match($input, '')) {
            throw new InvalidPhpRegularExpression($input, preg_last_error_msg());
        }

        // Check for lookaheads and lookbehinds
        if (preg_match('/(?<!\w)[\(\[]\?[:=!<]|[\(\[]\?[:=!<](?!\w)/', $input)) {
            throw new ExpressionContainsLookAheads($input);
        }
        // Check for nested repetitions
        $repetition = '((\{\d*,\d*\})|\*|\+)'; // {\d,\d} or * or +
        if (preg_match('/' . $repetition . '\)*' . $repetition . '/', $input)) {
            throw new ExpressionContainsRepeatsInRepeats($input);
        }
    }
}
