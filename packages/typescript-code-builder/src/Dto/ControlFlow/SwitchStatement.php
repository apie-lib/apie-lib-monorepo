<?php
namespace Apie\TypescriptCodeBuilder\Dto\ControlFlow;

use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\Lists\SwitchCaseList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;
use Apie\TypescriptCodeBuilder\Utils\ControlFlowUtils;

class SwitchStatement implements TypescriptFileExpressionInterface
{
    public function __construct(
        public TypescriptFileExpressionInterface $expression,
        public SwitchCaseList $cases
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
        $definitions = ControlFlowUtils::mergeDefinitions($this->expression);
        foreach ($this->cases as $case) {
            if ($case->condition) {
                foreach ($case->condition->needsDefinitions() as $definition) {
                    $definitions = $definitions->append($definition);
                }
            }
            foreach ($case->codeList as $code) {
                foreach ($code->needsDefinitions() as $definition) {
                    $definitions = $definitions->append($definition);
                }
            }
        }
        return $definitions;
    }
    private function render(bool $typescript): string
    {
        $expression = $typescript ? $this->expression->toTypescript() : $this->expression->toJavascript();
        $lines = ['switch (' . $expression . ') {'];
        foreach ($this->cases as $case) {
            $condition = $case->condition ? ($typescript ? $case->condition->toTypescript() : $case->condition->toJavascript()) : 'default';
            $lines[] = '    ' . ($case->condition ? 'case ' . $condition : $condition) . ':';
            $body = ControlFlowUtils::renderBlock($case->codeList, $typescript);
            if ($body !== '') {
                $lines[] = ControlFlowUtils::indent($body);
            }
        }
        $lines[] = '}';
        return implode(PHP_EOL, $lines);
    }

    public function providesDefinitions(bool $applyBlockScope): JavascriptIdentifierList
    {
        return new JavascriptIdentifierList();
    }
}
