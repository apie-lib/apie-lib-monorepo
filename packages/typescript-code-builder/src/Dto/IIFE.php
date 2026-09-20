<?php
namespace Apie\TypescriptCodeBuilder\Dto;

use Apie\TypescriptCodeBuilder\Lists\CodeList;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;

/**
 * Represents a IIFE: an inmediately invoke function expression.
 */
class IIFE implements TypescriptFileExpressionInterface
{
    public function __construct(
        public CodeList $codeList
    ) {
    }
    public function toTypescript(): string
    {
        $list = [];
        foreach ($this->codeList as $code) {
            $codeRow = $code->toTypescript();
            if ($codeRow) {
                $list[] = $codeRow;
            }
        }
        $firstPrefix = empty($list) ? '' : '    ';

        return '(function(){' . PHP_EOL . $firstPrefix . implode(PHP_EOL . '    ', $list) . PHP_EOL . '}());';
    }
    public function toJavascript(): string
    {
        $list = [];
        foreach ($this->codeList as $code) {
            $codeRow = $code->toJavascript();
            if ($codeRow) {
                $list[] = $codeRow;
            }
        }

        $firstPrefix = empty($list) ? '' : '    ';

        return '(function(){' . PHP_EOL . $firstPrefix . implode(PHP_EOL . '    ', $list) . PHP_EOL . '}());';
    }
    public function providesDefinitions(): JavascriptIdentifierList
    {
        return new JavascriptIdentifierList();
    }
    public function needsDefinitions(): JavascriptIdentifierList
    {
        $provides = [];
        foreach ($this->codeList as $code) {
            foreach ($code->providesDefinitions() as $definition) {
                $provides[$definition->toNative()] = true;
            }
        }
        $list = [];
        foreach ($this->codeList as $code) {
            foreach ($code->needsDefinitions() as $definition) {
                $key = $definition->toNative();
                if (empty($provides[$key])) {
                    $list[$key] = $definition;
                }
            }
        }
        return new JavascriptIdentifierList($list);
    }
}
