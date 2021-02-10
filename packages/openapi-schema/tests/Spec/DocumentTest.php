<?php


namespace Apie\Tests\OpenapiSchema\Spec;

use Apie\CompositeValueObjects\Exceptions\IgnoredKeysException;
use Apie\OpenapiSchema\Spec\Document;
use Apie\TypeJuggling\Exceptions\MissingValueException;
use Apie\ValueObjects\ValueObjectInterface;

class DocumentTest extends SpecTestCase
{
    protected function createFromInput($input): ValueObjectInterface
    {
        return Document::fromNative($input);
    }

    protected function getFolderName(): string
    {
        return 'Document';
    }

    protected function mapFolderToException(string $folderName): string
    {
        if ($folderName === 'missing') {
            return MissingValueException::class;
        }
        return IgnoredKeysException::class;
    }
}
