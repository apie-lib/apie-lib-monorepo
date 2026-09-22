<?php
namespace Apie\TypescriptCodeBuilder\Dto\Expressions;

use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;

class IdentifierExpression implements TypescriptFileExpressionInterface
{
    public function __construct(public JavascriptIdentifier $name)
    {
    }

    public function toTypescript(): string
    {
        return $this->name->toNative();
    }
    public function toJavascript(): string
    {
        return $this->name->toNative();
    }
    public function providesDefinitions(bool $applyBlockScope): JavascriptIdentifierList
    {
        return new JavascriptIdentifierList();
    }
    public function needsDefinitions(): JavascriptIdentifierList
    {
        return new JavascriptIdentifierList([$this->name]);
    }
}
