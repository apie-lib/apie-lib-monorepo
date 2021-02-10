<?php


namespace Apie\Tests\OpenapiSchema\Spec;

use Apie\OpenapiSchema\Spec\Discriminator;
use Apie\TypeJuggling\Exceptions\MissingValueException;
use Apie\ValueObjects\ValueObjectInterface;

class DiscriminatorTest extends SpecTestCase
{
    protected function createFromInput($input): ValueObjectInterface
    {
        return Discriminator::fromNative($input);
    }

    protected function getFolderName(): string
    {
        return 'Discriminator';
    }

    protected function mapFolderToException(string $folderName): string
    {
        return MissingValueException::class;
    }
}
