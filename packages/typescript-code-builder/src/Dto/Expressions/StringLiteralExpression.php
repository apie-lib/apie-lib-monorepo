<?php
namespace Apie\TypescriptCodeBuilder\Dto\Expressions;

use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;

class StringLiteralExpression implements TypescriptFileExpressionInterface
{
    public function __construct(public string $value)
    {
    }

    public function toTypescript(): string
    {
        return json_encode($this->value, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES);
    }
    public function toJavascript(): string
    {
        return $this->toTypescript();
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
