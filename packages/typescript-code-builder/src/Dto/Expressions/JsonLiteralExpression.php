<?php
namespace Apie\TypescriptCodeBuilder\Dto\Expressions;

use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;

class JsonLiteralExpression implements TypescriptFileExpressionInterface
{
    public function __construct(public mixed $value, public bool $prettified = false)
    {
    }

    public function toTypescript(): string
    {
        if ($this->prettified) {
            return json_encode($this->value, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        }
        return 'JSON.parse('
            . json_encode(
                json_encode($this->value, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES),
                JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES
            )
            .')';
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
