<?php
namespace Apie\TypescriptCodeBuilder\Dto\Expressions;

use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;

class NullExpression implements TypescriptFileExpressionInterface
{
    public function toTypescript(): string
    {
        return 'null';
    }
    public function toJavascript(): string
    {
        return 'null';
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
