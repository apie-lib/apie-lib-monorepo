<?php
namespace Apie\TypescriptCodeBuilder\Dto;

use Apie\Core\Identifiers\Identifier;
use Apie\Core\Lists\IdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;
use Apie\TypescriptCodeBuilder\TypescriptTypeDeclarationInterface;

class TypescriptDeclaration implements TypescriptFileExpressionInterface
{
    public function __construct(
        public Identifier $name,
        public TypescriptTypeDeclarationInterface $typehint
    ) {
    }

    public function toTypescript(): string
    {
        return 'type ' . $this->name . ' = ' . $this->typehint->toTypescript() . ';';
    }
    public function toJavascript(): string
    {
        return '';
    }
    public function providesDefinitions(): IdentifierList
    {
        return new IdentifierList([$this->name]);
    }
    public function needsDefinitions(): IdentifierList
    {
        return $this->typehint->needsDefinitions();
    }
}
