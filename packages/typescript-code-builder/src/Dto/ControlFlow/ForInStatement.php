<?php
namespace Apie\TypescriptCodeBuilder\Dto\ControlFlow;

use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;
use Apie\TypescriptCodeBuilder\Utils\ControlFlowUtils;

class ForInStatement implements TypescriptFileExpressionInterface
{
    public function __construct(
        public TypescriptFileExpressionInterface $variable,
        public TypescriptFileExpressionInterface $iterable,
        public BlockStatement $codeList,
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
        $definitions = ControlFlowUtils::mergeDefinitions($this->variable, $this->iterable);
        foreach ($this->codeList->needsDefinitions() as $definition) {
            $definitions = $definitions->append($definition);
        }
        return $definitions;
    }
    protected function render(bool $typescript): string
    {
        $variable = $typescript ? $this->variable->toTypescript() : $this->variable->toJavascript();
        $iterable = $typescript ? $this->iterable->toTypescript() : $this->iterable->toJavascript();
        $body = ControlFlowUtils::renderBlock($this->codeList->codeList, $typescript);
        return 'for (' . rtrim($variable, ';') . ' in ' . $iterable . ') {' . ($body === '' ? '' : PHP_EOL . $body) . PHP_EOL . '}';
    }

    public function providesDefinitions(bool $applyBlockScope): JavascriptIdentifierList
    {
        return $this->codeList->providesDefinitions(true);
    }
}
