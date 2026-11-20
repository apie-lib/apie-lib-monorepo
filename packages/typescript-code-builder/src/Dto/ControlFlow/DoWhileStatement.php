<?php
namespace Apie\TypescriptCodeBuilder\Dto\ControlFlow;

use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;
use Apie\TypescriptCodeBuilder\Utils\ControlFlowUtils;

class DoWhileStatement implements TypescriptFileExpressionInterface
{
    public function __construct(public BlockStatement $codeList, public TypescriptFileExpressionInterface $condition)
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
        $body = ControlFlowUtils::renderBlock($this->codeList->codeList, $typescript);
        $condition = $typescript ? $this->condition->toTypescript() : $this->condition->toJavascript();
        return 'do {' . ($body === '' ? '' : PHP_EOL . $body) . PHP_EOL . '} while (' . $condition . ');';
    }

    public function providesDefinitions(bool $applyBlockScope): JavascriptIdentifierList
    {
        return $this->codeList->providesDefinitions(true);
    }
}
