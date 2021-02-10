<?php


namespace Apie\Tests\OpenapiSchema\Spec;

use Apie\OpenapiSchema\Spec\Callback;
use Apie\ValueObjects\ValueObjectInterface;

class CallbackTest extends SpecTestCase
{
    protected function createFromInput($input): ValueObjectInterface
    {
        return Callback::fromNative($input);
    }

    protected function getFolderName(): string
    {
        return 'Callback';
    }

    protected function mapFolderToException(string $folderName): string
    {
        // TODO: Implement mapFolderToException() method.
    }
}
