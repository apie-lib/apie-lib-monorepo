<?php
namespace Apie\CommonValueObjects\Regexes;

use Apie\CommonValueObjects\Exceptions\InvalidPhpRegularExpression;
use Apie\Core\Attributes\FakeMethod;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;
use Apie\Core\ValueObjects\IsStringValueObject;
use Faker\Generator;

#[FakeMethod("createRandom")]
class PhpRegularExpression implements StringValueObjectInterface
{
    use IsStringValueObject;

    public static function validate(string $input): void
    {
        if (false === @preg_match($input, '')) {
            throw new InvalidPhpRegularExpression($input, preg_last_error_msg());
        }
    }

    public static function createRandom(Generator $generator): self
    {
        $parts = ['[A-Z]', '[a-z]', '\d', '(yes|no)'];
        $repeats = ['{1,2}', '*', '+', ''];
        $count = $generator->random_int(1, 8);
        $content = [];
        for ($i = 0; $i < $count; $i++) {
            $content[] = $generator->randomElement($parts);
            $content[] = $generator->randomElement($repeats);
        }
        return new self('/' . implode('', $content) . '/' . $generator->randomElement(['i', 'u', 'n']));
    }
}