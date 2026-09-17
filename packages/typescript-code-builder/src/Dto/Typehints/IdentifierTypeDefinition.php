<?php
namespace Apie\TypescriptCodeBuilder\Dto\Typehints;

use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptTypeDeclarationInterface;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;

class IdentifierTypeDefinition implements TypescriptTypeDeclarationInterface
{
    public function __construct(
        public JavascriptIdentifier $name
    ) {

    }
    public function toTypescript(): string
    {
        return $this->name->toNative();
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
        return new JavascriptIdentifierList([$this->name]);
    }
}
