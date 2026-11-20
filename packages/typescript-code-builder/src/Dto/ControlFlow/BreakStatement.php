<?php
namespace Apie\TypescriptCodeBuilder\Dto\ControlFlow;

use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;

class BreakStatement implements TypescriptFileExpressionInterface
{
    public function __construct(public ?string $label = null)
    {
    }
    public function toTypescript(): string
    {
        return 'break' . ($this->label ? ' ' . $this->label : '') . ';';
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
