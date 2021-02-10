<?php


namespace Apie\Tests\OpenapiSchema\Spec;

use Apie\OpenapiSchema\Exceptions\ExampleValueAndExternalValueAreMutuallyExclusive;
use Apie\OpenapiSchema\Spec\Example;
use Apie\ValueObjects\Exceptions\InvalidValueForValueObjectException;
use Apie\ValueObjects\ValueObjectInterface;

class ExampleTest extends SpecTestCase
{
    protected function createFromInput($input): ValueObjectInterface
    {
        return Example::fromNative($input);
    }

    protected function getFolderName(): string
    {
        return 'Example';
    }

    protected function mapFolderToException(string $folderName): string
    {
        return $folderName === 'external_no_url'
            ? InvalidValueForValueObjectException::class
            : ExampleValueAndExternalValueAreMutuallyExclusive::class;
    }
}
