<?php
namespace Apie\TypescriptCodeBuilder\Dto\Typehints;

use Apie\Core\Identifiers\Identifier;
use Apie\Core\Lists\IdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptTypeDeclarationInterface;

class IdentifierTypeDefinition implements TypescriptTypeDeclarationInterface
{
    public function __construct(
        public Identifier $name
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
    public function providesDefinitions(): IdentifierList
    {
        return new IdentifierList();
    }
    public function needsDefinitions(): IdentifierList
    {
        return new IdentifierList([$this->name]);
    }
}