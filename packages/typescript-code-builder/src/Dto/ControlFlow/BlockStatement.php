<?php
namespace Apie\TypescriptCodeBuilder\Dto\ControlFlow;

use Apie\TypescriptCodeBuilder\Lists\CodeList;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;
use Apie\TypescriptCodeBuilder\Utils\ControlFlowUtils;

class BlockStatement implements TypescriptFileExpressionInterface
{
    public function __construct(public CodeList $codeList)
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

    public function providesDefinitions(bool $applyBlockScope): JavascriptIdentifierList
    {
        $definitions = new JavascriptIdentifierList();
        foreach ($this->codeList as $code) {
            $childApplyBlockScope = $applyBlockScope || $code instanceof self;
            foreach ($code->providesDefinitions($childApplyBlockScope) as $definition) {
                $definitions = $definitions->append($definition);
            }
        }
        return $definitions;
    }

    public function needsDefinitions(): JavascriptIdentifierList
    {
        $provides = ControlFlowUtils::blockDefinitions($this)->toStringArray();
        $needs = new JavascriptIdentifierList();
        foreach ($this->codeList as $code) {
            foreach ($code->needsDefinitions() as $definition) {
                if (!in_array($definition->toNative(), $provides, true)) {
                    $needs = $needs->append($definition);
                }
            }
        }
        return $needs;
    }

    private function render(bool $typescript): string
    {
        $body = ControlFlowUtils::renderBlock($this->codeList, $typescript);
        return '{' . ($body === '' ? '' : PHP_EOL . $body) . PHP_EOL . '}';
    }
}
