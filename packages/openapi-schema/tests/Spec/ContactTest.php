<?php


namespace Apie\Tests\OpenapiSchema\Spec;

use Apie\OpenapiSchema\Spec\Contact;
use Apie\ValueObjects\Exceptions\InvalidValueForValueObjectException;
use Apie\ValueObjects\ValueObjectInterface;

class ContactTest extends SpecTestCase
{
    protected function createFromInput($input): ValueObjectInterface
    {
        return Contact::fromNative($input);
    }

    protected function getFolderName(): string
    {
        return 'Contact';
    }

    protected function mapFolderToException(string $folderName): string
    {
        return InvalidValueForValueObjectException::class;
    }
}
