<?php
namespace Apie\TypescriptCodeBuilder\Dto;

use Apie\TypescriptCodeBuilder\Enums\VariableDeclarationKind;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;
use Apie\TypescriptCodeBuilder\TypescriptTypeDeclarationInterface;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;

class VariableAssignment implements TypescriptFileExpressionInterface
{
    public function __construct(
        public VariableDeclarationKind $kind,
        public JavascriptIdentifier $name,
        public TypescriptFileExpressionInterface $expression,
        public ?TypescriptTypeDeclarationInterface $typehint = null,
    ) {
    }

    public function toTypescript(): string
    {
        $typehint = $this->typehint ? ': ' . $this->typehint->toTypescript() : '';
        return $this->kind->value . ' ' . $this->name->toNative() . $typehint . ' = ' . $this->expression->toTypescript() . ';';
    }

    public function toJavascript(): string
    {
        return $this->kind->value . ' ' . $this->name->toNative() . ' = ' . $this->expression->toJavascript() . ';';
    }

    public function providesDefinitions(): JavascriptIdentifierList
    {
        return new JavascriptIdentifierList([$this->name]);
    }

    public function needsDefinitions(): JavascriptIdentifierList
    {
        $definitions = $this->typehint?->needsDefinitions() ?? new JavascriptIdentifierList();
        foreach ($this->expression->needsDefinitions() as $definition) {
            $definitions->append($definition);
        }
        return $definitions;
    }
}