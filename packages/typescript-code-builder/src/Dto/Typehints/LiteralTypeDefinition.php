<?php
namespace Apie\TypescriptCodeBuilder\Dto\Typehints;

use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptTypeDeclarationInterface;

/**
 * Typescript definition for a string, number, boolean, or null literal type.
 */
class LiteralTypeDefinition implements TypescriptTypeDeclarationInterface
{
    public function __construct(
        public string|int|float|bool|null $value,
    ) {
    }

    public function toTypescript(): string
    {
        return json_encode($this->value, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES);
    }

    public function toJavascript(): string
    {
        return '';
    }

    public function providesDefinitions(): JavascriptIdentifierList
    {
        return new JavascriptIdentifierList();
    }

    public function needsDefinitions(): JavascriptIdentifierList
    {
        return new JavascriptIdentifierList();
    }
}