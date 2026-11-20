<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\ControlFlow;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\ControlFlow\BlockStatement;
use Apie\TypescriptCodeBuilder\Dto\ControlFlow\IfStatement;
use Apie\TypescriptCodeBuilder\Dto\RawJavascript;
use Apie\TypescriptCodeBuilder\Lists\CodeList;
use PHPUnit\Framework\Attributes\Test;

class IfStatementTest extends ObjectTestCase
{
    public static function className(): string
    {
        return IfStatement::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'condition' => ['$ref' => '#/components/schemas/TypescriptFileExpression-post'],
                'then' => ['$ref' => '#/components/schemas/BlockStatement-post'],
                'else' => ['$ref' => '#/components/schemas/BlockStatement-nullable-post'],
            ],
            'required' => ['condition', 'then'],
        ];
    }

    #[Test]
    public function if_statement_renders_then_and_else_blocks(): void
    {
        $if = new IfStatement(
            new RawJavascript('enabled'),
            new BlockStatement(new CodeList([
                new RawJavascript('run();'),
            ])),
            new BlockStatement(new CodeList([
                new RawJavascript('fallback();'),
            ])),
        );

        $this->assertSame("if (enabled) {\n    run();\n} else {\n    fallback();\n}", $if->toTypescript());
    }
}
