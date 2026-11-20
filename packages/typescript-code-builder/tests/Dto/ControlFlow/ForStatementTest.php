<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\ControlFlow;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\ControlFlow\BlockStatement;
use Apie\TypescriptCodeBuilder\Dto\ControlFlow\ForStatement;
use Apie\TypescriptCodeBuilder\Dto\RawJavascript;
use Apie\TypescriptCodeBuilder\Lists\CodeList;
use PHPUnit\Framework\Attributes\Test;

class ForStatementTest extends ObjectTestCase
{
    public static function className(): string
    {
        return ForStatement::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'initialization' => ['$ref' => '#/components/schemas/TypescriptFileExpression-nullable-post'],
                'condition' => ['$ref' => '#/components/schemas/TypescriptFileExpression-nullable-post'],
                'increment' => ['$ref' => '#/components/schemas/TypescriptFileExpression-nullable-post'],
                'codeList' => ['$ref' => '#/components/schemas/BlockStatement-post'],
            ],
            'required' => ['initialization', 'condition', 'increment', 'codeList'],
        ];
    }

    #[Test]
    public function for_statement_renders_expected_output(): void
    {
        $statement = new ForStatement(
            new RawJavascript('let index = 0'),
            new RawJavascript('index < 10'),
            new RawJavascript('index++'),
            new BlockStatement(new CodeList([new RawJavascript('doThing();')])),
        );

        $this->assertSame("for (let index = 0; index < 10; index++) {\n    doThing();\n}", $statement->toTypescript());
    }
}
