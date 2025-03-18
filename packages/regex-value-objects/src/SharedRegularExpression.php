<?php
namespace Apie\RegexValueObjects;

use Faker\Generator;

trait SharedRegularExpression
{
    public function getDelimiter(): string
    {
        return substr($this->internal, 0, 1);
    }

    public function getModifiers(): string
    {
        $delimiter = $this->getDelimiter();
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
