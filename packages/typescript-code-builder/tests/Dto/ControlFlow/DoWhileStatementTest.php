<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\ControlFlow;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\ControlFlow\BlockStatement;
use Apie\TypescriptCodeBuilder\Dto\ControlFlow\DoWhileStatement;
use Apie\TypescriptCodeBuilder\Dto\RawJavascript;
use Apie\TypescriptCodeBuilder\Lists\CodeList;
use PHPUnit\Framework\Attributes\Test;

class DoWhileStatementTest extends ObjectTestCase
{
    public static function className(): string
    {
        return DoWhileStatement::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'codeList' => ['$ref' => '#/components/schemas/BlockStatement-post'],
                'condition' => ['$ref' => '#/components/schemas/TypescriptFileExpression-post'],
            ],
            'required' => ['codeList', 'condition'],
        ];
    }

    #[Test]
    public function do_while_statement_renders_expected_output(): void
    {
        $statement = new DoWhileStatement(
            new BlockStatement(new CodeList([new RawJavascript('doThing();')])),
            new RawJavascript('isReady'),
        );

        $this->assertSame("do {\n    doThing();\n} while (isReady);", $statement->toTypescript());
    }
}
