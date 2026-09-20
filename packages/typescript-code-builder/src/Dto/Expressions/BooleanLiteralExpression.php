<?php
namespace Apie\TypescriptCodeBuilder\Dto\Expressions;

use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;

class BooleanLiteralExpression implements TypescriptFileExpressionInterface
{
    public function __construct(public bool $value)
    {
    }

    public function toTypescript(): string
    {
        return $this->value ? 'true' : 'false';
    }
    public function toJavascript(): string
    {
        return $this->value ? 'true' : 'false';
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
