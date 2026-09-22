<?php
namespace Apie\TypescriptCodeBuilder\Dto\Expressions;

use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\Lists\TypescriptExpressionHashmap;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;

class ObjectExpression implements TypescriptFileExpressionInterface
{
    public function __construct(public TypescriptExpressionHashmap $properties)
    {
    }

    public function toTypescript(): string
    {
        $properties = [];
        foreach ($this->properties as $name => $expression) {
            $properties[] = $name . ': ' . $expression->toTypescript();
        }
        return '{ ' . implode(', ', $properties) . ' }';
    }

    public function toJavascript(): string
    {
        $properties = [];
        foreach ($this->properties as $name => $expression) {
            $properties[] = $name . ': ' . $expression->toJavascript();
        }
        return '{ ' . implode(', ', $properties) . ' }';
    }
    public function providesDefinitions(bool $applyBlockScope): JavascriptIdentifierList
    {
        return new JavascriptIdentifierList();
    }
    public function needsDefinitions(): JavascriptIdentifierList
    {
        $definitions = new JavascriptIdentifierList();
        foreach ($this->properties as $expression) {
            foreach ($expression->needsDefinitions() as $definition) {
                $definitions = $definitions->append($definition);
            }
        }
        return $definitions;
    }
}
