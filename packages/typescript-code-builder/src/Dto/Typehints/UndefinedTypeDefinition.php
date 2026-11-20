<?php
namespace Apie\TypescriptCodeBuilder\Dto\Typehints;

use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptTypeDeclarationInterface;

/**
 * Typescript definition for the undefined type.
 */
class UndefinedTypeDefinition implements TypescriptTypeDeclarationInterface
{
    public function toTypescript(): string
    {
        return 'undefined';
    }

    public function toJavascript(): string
    {
        return '';
    }

    public function providesDefinitions(bool $applyBlockScope): JavascriptIdentifierList
    {
        return new JavascriptIdentifierList();
    }

    public function needsDefinitions(): JavascriptIdentifierList
    {
        return new JavascriptIdentifierList();
    }
}
