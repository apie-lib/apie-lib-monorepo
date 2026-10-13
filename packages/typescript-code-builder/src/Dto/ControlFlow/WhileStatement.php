<?php
namespace Apie\TypescriptCodeBuilder\Dto\ControlFlow;

use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;
use Apie\TypescriptCodeBuilder\Utils\ControlFlowUtils;

class WhileStatement implements TypescriptFileExpressionInterface
{
    public function __construct(public TypescriptFileExpressionInterface $condition, public BlockStatement $codeList)
    {
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
        return ControlFlowUtils::mergeDefinitions($this->condition, $this->codeList);
    }
    private function render(bool $typescript): string
    {
        $condition = $typescript ? $this->condition->toTypescript() : $this->condition->toJavascript();
        $body = ControlFlowUtils::renderBlock($this->codeList->codeList, $typescript);
        return 'while (' . $condition . ') {' . ($body === '' ? '' : PHP_EOL . $body) . PHP_EOL . '}';
    }

    public function providesDefinitions(bool $applyBlockScope): JavascriptIdentifierList
    {
        return $this->codeList->providesDefinitions(true);
    }
}
