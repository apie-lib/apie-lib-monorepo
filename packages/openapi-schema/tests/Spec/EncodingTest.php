<?php


namespace Apie\Tests\OpenapiSchema\Spec;

use Apie\OpenapiSchema\Spec\Encoding;
use Apie\ValueObjects\Exceptions\InvalidValueForValueObjectException;
use Apie\ValueObjects\ValueObjectInterface;

class EncodingTest extends SpecTestCase
{
    protected function createFromInput($input): ValueObjectInterface
    {
        return Encoding::fromNative($input);
    }

    protected function getFolderName(): string
    {
        return 'Encoding';
    }

    protected function mapFolderToException(string $folderName): string
    {
        return InvalidValueForValueObjectException::class;
    }
}
