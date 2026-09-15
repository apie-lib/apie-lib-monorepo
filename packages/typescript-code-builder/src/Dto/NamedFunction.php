<?php
namespace Apie\TypescriptCodeBuilder\Dto;

use Apie\Core\Identifiers\Identifier;
use Apie\Core\Lists\IdentifierList;
use Apie\TypescriptCodeBuilder\Lists\ArgumentList;
use Apie\TypescriptCodeBuilder\Lists\CodeList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;

class NamedFunction implements TypescriptFileExpressionInterface
{
    public function __construct(
        public Identifier $name,
        public ArgumentList $arguments,
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
        return 'function ' . $this->name . '(' . $this->arguments->toTypescript() . ') {' . PHP_EOL . $firstPrefix . implode(PHP_EOL . '    ', $list) . PHP_EOL . '}';
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
        return 'function ' . $this->name . '(' . $this->arguments->toJavascript() . ') {' . PHP_EOL . $firstPrefix . implode(PHP_EOL . '    ', $list) . PHP_EOL . '}';
    }
    
    public function providesDefinitions(): IdentifierList
    {
        return new IdentifierList([$this->name]);
    }
    public function needsDefinitions(): IdentifierList
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
        foreach($this->arguments as $argument) {
            foreach ($argument->needsDefinitions() as $definition) {
                $list[$definition->toNative()] = $definition;
            }
        }
        return new IdentifierList($list);
    }
}