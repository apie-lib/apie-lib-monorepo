<?php


namespace Apie\CommonValueObjects;


use Apie\ValueObjects\StringTrait;
use Apie\ValueObjects\ValueObjectInterface;

/**
 * Value object for a regular expression (without the delimiters).
 *
 * @see https://www.ecma-international.org/ecma-262/5.1/#sec-15.10.1
 */
class JavascriptRegularExpression implements ValueObjectInterface
{
    use StringTrait;

    /**
     * {@inheritDoc}
     */
    protected function validValue(string $value): bool
    {
        @preg_match(
            '/' . str_replace('/', '\\/', $value) . '/',
            ''
        );
        return preg_last_error() === PREG_NO_ERROR;
    }

    /**
     * {@inheritDoc}
     */
    protected function sanitizeValue(string $value): string
    {
        return $value;
    }

    public function test(string $value): bool
    {
        return preg_match(
            '/' . str_replace('/', '\\/', $this->toNative()) . '/',
            $value
        ) ? true : false;
    }

    public function matchOnce(string $value): ?array
    {
        $matches = [];
        if (!preg_match(
            '/' . str_replace('/', '\\/', $this->toNative()) . '/',
            $value,
            $matches
        )) {
            return null;
        }
        return $matches;
    }

    public function matchAll(string $value): array
    {
        $matches = [];
        if (!preg_match_all(
            '/' . str_replace('/', '\\/', $this->toNative()) . '/',
            $value,
            $matches
        )) {
            return [];
        }
        return $matches;
    }
}