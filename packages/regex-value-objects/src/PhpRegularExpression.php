<?php
namespace Apie\RegexValueObjects;

use Apie\Core\Attributes\FakeMethod;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;
use Apie\Core\ValueObjects\IsStringValueObject;
use Apie\RegexValueObjects\Exceptions\InvalidPhpRegularExpression;
use Faker\Generator;

#[FakeMethod("createRandom")]
final class PhpRegularExpression implements StringValueObjectInterface
{
    use IsStringValueObject;

    public static function validate(string $input): void
    {
        if (false === @preg_match($input, '')) {
            throw new InvalidPhpRegularExpression($input, preg_last_error_msg());
        }
    }

    public function getDelimiter(): string
    {
        return substr($this->internal, 0, 1);
    }

    public function getModifiers(): string
    {
        $delimiter = $this->getDelimiter();
        //return strrchr($this->internal, $delimiter);
        return substr($this->internal, 1 + strrpos($this->internal, $delimiter));
    }

    public static function createRandom(Generator $generator): self
    {
        $parts = ['[A-Z]', '[a-z]', '\d', '(yes|no)'];
        $repeats = ['{1,2}', '*', '+', ''];
        $count = $generator->numberBetween(1, 8);
        $content = [];
        for ($i = 0; $i < $count; $i++) {
            $content[] = $generator->randomElement($parts);
            $content[] = $generator->randomElement($repeats);
        }
        return new self('/' . implode('', $content) . '/' . $generator->randomElement(['i', 'u', '']));
    }
}
