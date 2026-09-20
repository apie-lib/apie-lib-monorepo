<?php
namespace Apie\TypescriptCodeBuilder\Dto\Expressions;

use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;

class UndefinedExpression implements TypescriptFileExpressionInterface
{
    public function toTypescript(): string
    {
        return 'undefined';
    }
    public function toJavascript(): string
    {
        return 'undefined';
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
