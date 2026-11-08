<?php
namespace Apie\TypescriptCodeBuilder\Dto\ControlFlow;

use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;
use Apie\TypescriptCodeBuilder\Utils\ControlFlowUtils;

class ForStatement implements TypescriptFileExpressionInterface
{
    public function __construct(
        public ?TypescriptFileExpressionInterface $initialization,
        public ?TypescriptFileExpressionInterface $condition,
        public ?TypescriptFileExpressionInterface $increment,
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
        $expressions = array_values(array_filter([
            $this->initialization,
            $this->condition,
            $this->increment,
        ]));
        $definitions = ControlFlowUtils::mergeDefinitions(...$expressions);
        foreach ($this->codeList->needsDefinitions() as $definition) {
            $definitions = $definitions->append($definition);
        }
        return $definitions;
    }

    private function render(bool $typescript): string
    {
        $parts = [];
        foreach ([$this->initialization, $this->condition, $this->increment] as $expression) {
            $parts[] = $expression ? ($typescript ? $expression->toTypescript() : $expression->toJavascript()) : '';
        }
        $parts[0] = rtrim($parts[0], ';');
        $body = ControlFlowUtils::renderBlock($this->codeList->codeList, $typescript);
        return 'for (' . implode('; ', $parts) . ') {' . ($body === '' ? '' : PHP_EOL . $body) . PHP_EOL . '}';
    }

    public function providesDefinitions(bool $applyBlockScope): JavascriptIdentifierList
    {
        return $this->codeList->providesDefinitions(true);
    }
}
