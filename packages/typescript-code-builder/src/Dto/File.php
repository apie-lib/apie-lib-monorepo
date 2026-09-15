<?php
namespace Apie\TypescriptCodeBuilder\Dto;

use Apie\Core\Lists\IdentifierList;
use Apie\TypescriptCodeBuilder\Lists\CodeList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;

/**
 * Represents a javascript or typescript file.
 */
class File implements TypescriptFileExpressionInterface
{
    public function __construct(
        private CodeList $codeList
    ) {
    }
    public function toTypescript(): string
    {
        $list = [];
        foreach ($this->codeList as $code) {
            $list[] = $code->toTypescript();
        }

        return implode(PHP_EOL, $list);
    }
    public function toJavascript(): string
    {
        $list = [];
        foreach ($this->codeList as $code) {
            $list[] = $code->toJavascript();
        }

        return implode(PHP_EOL, $list);
    }
    public function providesDefinitions(): IdentifierList
    {
        return new IdentifierList();
    }
    public function needsDefinitions(): IdentifierList
    {
        return new IdentifierList();
    }
}
