<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\ControlFlow;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\ControlFlow\BlockStatement;
use Apie\TypescriptCodeBuilder\Dto\ControlFlow\ForInStatement;
use Apie\TypescriptCodeBuilder\Dto\RawJavascript;
use Apie\TypescriptCodeBuilder\Lists\CodeList;
use PHPUnit\Framework\Attributes\Test;

class ForInStatementTest extends ObjectTestCase
{
    public static function className(): string
    {
        return ForInStatement::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'variable' => ['$ref' => '#/components/schemas/TypescriptFileExpression-post'],
                'iterable' => ['$ref' => '#/components/schemas/TypescriptFileExpression-post'],
                'codeList' => ['$ref' => '#/components/schemas/BlockStatement-post'],
            ],
            'required' => ['variable', 'iterable', 'codeList'],
        ];
    }

    #[Test]
    public function for_in_statement_renders_expected_output(): void
    {
        $statement = new ForInStatement(
            new RawJavascript('key'),
            new RawJavascript('items'),
            new BlockStatement(new CodeList([new RawJavascript('doThing();')])),
        );

        $this->assertSame("for (key in items) {\n    doThing();\n}", $statement->toTypescript());
    }
}
