<?php
namespace Apie\TypescriptCodeBuilder\Dto\Typehints;

use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptTypeDeclarationInterface;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;

class TypeGuardDefinition implements TypescriptTypeDeclarationInterface
{
    public function __construct(
        public JavascriptIdentifier $value,
        public TypescriptTypeDeclarationInterface $type,
    ) {
    }

    public function toTypescript(): string
    {
        return $this->value->toNative() . ' is ' . $this->type->toTypescript();
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
        return $this->type->needsDefinitions();
    }
}
