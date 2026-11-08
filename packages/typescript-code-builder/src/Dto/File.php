<?php
namespace Apie\TypescriptCodeBuilder\Dto;

use Apie\TypescriptCodeBuilder\Lists\CodeList;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
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
            $script = $code->toTypescript();
            if ($script !== '') {
                $list[] = $script;
            }
        }

        return implode(PHP_EOL, $list);
    }
    public function toJavascript(): string
    {
        $list = [];
        foreach ($this->codeList as $code) {
            $script = $code->toJavascript();
            if ($script !== '') {
                $list[] = $script;
            }
        }

        return implode(PHP_EOL, $list);
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
