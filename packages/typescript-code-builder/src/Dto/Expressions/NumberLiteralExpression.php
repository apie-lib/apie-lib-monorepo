<?php
namespace Apie\TypescriptCodeBuilder\Dto\Expressions;

use Apie\Core\ValueObjects\Interfaces\HasRegexValueObjectInterface;
use Apie\Core\ValueObjects\IsStringWithRegexValueObject;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;

class NumberLiteralExpression implements TypescriptFileExpressionInterface, HasRegexValueObjectInterface
{
    use IsStringWithRegexValueObject;

    public static function getRegularExpression(): string
    {
        return '/^(0[xX][0-9a-fA-F]+|0[bB][01]+|0[oO][0-7]+|(0|[1-9][0-9]*)(\.[0-9]+)?([eE][+-]?[0-9]+)?)$/D';
    }

    public function toTypescript(): string
    {
        return $this->toNative();
    }
    public function toJavascript(): string
    {
        return $this->toNative();
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
