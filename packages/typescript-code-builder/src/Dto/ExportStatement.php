<?php
namespace Apie\TypescriptCodeBuilder\Dto;

use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;

class ExportStatement implements TypescriptFileExpressionInterface
{
    public function __construct(public JavascriptIdentifierList $exports)
    {
    }

    public function toTypescript(): string
    {
        return 'export { ' . implode(', ', $this->exports->toStringArray()) . ' }';
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
        return $this->exports;
    }
}
