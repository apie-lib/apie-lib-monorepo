<?php
namespace Apie\TypescriptCodeBuilder\Dto\ControlFlow;

use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;
use Apie\TypescriptCodeBuilder\Utils\ControlFlowUtils;

class IfStatement implements TypescriptFileExpressionInterface
{
    public function __construct(
        public TypescriptFileExpressionInterface $condition,
        public BlockStatement $then,
        public ?BlockStatement $else = null,
    ) {
    }

    public function toTypescript(): string
    {
        return $this->render(true);
    }

    public function toJavascript(): string
    {
        return $this->render(false);
    }

    public function needsDefinitions(): JavascriptIdentifierList
    {
        $definitions = ControlFlowUtils::mergeDefinitions($this->condition);
        foreach ([$this->then, $this->else] as $block) {
            if (!$block) {
                continue;
            }
            foreach ($block->needsDefinitions() as $definition) {
                $definitions = $definitions->append($definition);
            }
        }
        return $definitions;
    }

    private function render(bool $typescript): string
    {
        $result = 'if (' . ($typescript ? $this->condition->toTypescript() : $this->condition->toJavascript()) . ') {';
        $body = ControlFlowUtils::renderBlock($this->then->codeList, $typescript);
        if ($body !== '') {
            $result .= PHP_EOL . $body;
        }
        $result .= PHP_EOL . '}';
        if ($this->else) {
            $result .= ' else {';
            $body = ControlFlowUtils::renderBlock($this->else->codeList, $typescript);
            if ($body !== '') {
                $result .= PHP_EOL . $body;
            }
            $result .= PHP_EOL . '}';
        }
        return $result;
    }

    public function providesDefinitions(bool $applyBlockScope): JavascriptIdentifierList
    {
        $definitions = $this->then->providesDefinitions(true);
        if ($this->else) {
            foreach ($this->else->providesDefinitions(true) as $definition) {
                $definitions = $definitions->append($definition);
            }
        }
        return $definitions;
    }
}
