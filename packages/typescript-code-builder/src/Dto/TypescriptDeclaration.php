<?php
namespace Apie\TypescriptCodeBuilder\Dto;

use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;
use Apie\TypescriptCodeBuilder\TypescriptTypeDeclarationInterface;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;

class TypescriptDeclaration implements TypescriptFileExpressionInterface
{
    public function __construct(
        public JavascriptIdentifier $name,
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
    public function providesDefinitions(): JavascriptIdentifierList
    {
        return new JavascriptIdentifierList([$this->name]);
    }
    public function needsDefinitions(): JavascriptIdentifierList
    {
        return $this->typehint->needsDefinitions();
    }
}
