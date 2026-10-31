<?php
namespace Apie\TypescriptCodeBuilder\Dto\ControlFlow;

use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;

class LabelStatement implements TypescriptFileExpressionInterface
{
    public function __construct(public string $label)
    {
    }
    public function toTypescript(): string
    {
        return $this->label . ':';
    }
    public function toJavascript(): string
    {
        return $this->toTypescript();
    }
    public function needsDefinitions(): JavascriptIdentifierList
    {
        return new JavascriptIdentifierList();
    }
    public function providesDefinitions(bool $applyBlockScope): JavascriptIdentifierList
    {
        return new JavascriptIdentifierList();
    }
}
