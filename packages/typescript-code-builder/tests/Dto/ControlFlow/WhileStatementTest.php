<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\ControlFlow;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\ControlFlow\BlockStatement;
use Apie\TypescriptCodeBuilder\Dto\ControlFlow\WhileStatement;
use Apie\TypescriptCodeBuilder\Dto\RawJavascript;
use Apie\TypescriptCodeBuilder\Lists\CodeList;
use PHPUnit\Framework\Attributes\Test;

class WhileStatementTest extends ObjectTestCase
{
    public static function className(): string
    {
        return WhileStatement::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'condition' => ['$ref' => '#/components/schemas/TypescriptFileExpression-post'],
                'codeList' => ['$ref' => '#/components/schemas/BlockStatement-post'],
            ],
            'required' => ['condition', 'codeList'],
        ];
    }

    #[Test]
    public function while_statement_renders_expected_output(): void
    {
        $statement = new WhileStatement(
            new RawJavascript('isReady'),
            new BlockStatement(new CodeList([new RawJavascript('doThing();')])),
        );

        $this->assertSame("while (isReady) {\n    doThing();\n}", $statement->toTypescript());
    }
}
