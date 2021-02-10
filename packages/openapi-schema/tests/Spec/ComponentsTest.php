<?php


namespace Apie\Tests\OpenapiSchema\Spec;

use Apie\OpenapiSchema\Spec\Components;
use Apie\ValueObjects\ValueObjectInterface;

class ComponentsTest extends SpecTestCase
{
    protected function createFromInput($input): ValueObjectInterface
    {
        return Components::fromNative($input);
    }

    protected function getFolderName(): string
    {
        return "Components";
    }

    protected function mapFolderToException(string $folderName): string
    {
        // TODO: Implement mapFolderToException() method.
    }
}
