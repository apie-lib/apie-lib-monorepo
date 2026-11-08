<?php
namespace Apie\IanaValueObjects;

trait IsSanitizingInput
{
    protected function convert(string $input): string
    {
        $inputLower = strtolower($input);
        $data = static::getData();

        return $data[$inputLower]['Subtag'] ?? $input;
    }
}
