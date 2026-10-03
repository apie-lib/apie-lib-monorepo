<?php
namespace Apie\TypescriptCodeBuilder\Utils;

use Apie\TypescriptCodeBuilder\Dto\ControlFlow\BlockStatement;
use Apie\TypescriptCodeBuilder\Lists\CodeList;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;

final class ControlFlowUtils
{
    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    public static function indent(string $code): string
    {
        return implode(PHP_EOL, array_map(
            static fn (string $line): string => '    ' . $line,
            explode(PHP_EOL, $code),
        ));
    }

    public static function renderBlock(CodeList $codeList, bool $typescript): string
    {
        $lines = [];
        foreach ($codeList as $code) {
            $line = $typescript ? $code->toTypescript() : $code->toJavascript();
            if (trim($line)) {
                $lines[] = $line;
            }
        }

        return self::indent(implode(PHP_EOL, $lines));
    }

    public static function mergeDefinitions(TypescriptFileExpressionInterface ...$expressions): JavascriptIdentifierList
    {
        $definitions = new JavascriptIdentifierList();
        foreach ($expressions as $expression) {
            foreach ($expression->needsDefinitions() as $definition) {
                $definitions = $definitions->append($definition);
            }
        }
        return $definitions;
    }

    public static function mergeCodeDefinitions(CodeList $codeList): JavascriptIdentifierList
    {
        $definitions = new JavascriptIdentifierList();
        foreach ($codeList as $code) {
            foreach ($code->needsDefinitions() as $definition) {
                $definitions = $definitions->append($definition);
            }
        }
        return $definitions;
    }

    public static function blockDefinitions(BlockStatement $block): JavascriptIdentifierList
    {
        $definitions = new JavascriptIdentifierList();
        foreach ($block->codeList as $code) {
            $provided = $code->providesDefinitions($code instanceof BlockStatement);
            foreach ($provided as $definition) {
                $definitions = $definitions->append($definition);
            }
        }
        return $definitions;
    }
}
