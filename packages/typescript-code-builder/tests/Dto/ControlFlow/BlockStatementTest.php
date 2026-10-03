<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\ControlFlow;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\ControlFlow\BlockStatement;
use Apie\TypescriptCodeBuilder\Dto\RawJavascript;
use Apie\TypescriptCodeBuilder\Dto\VariableAssignment;
use Apie\TypescriptCodeBuilder\Enums\VariableDeclarationKind;
use Apie\TypescriptCodeBuilder\Lists\CodeList;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;
use PHPUnit\Framework\Attributes\Test;

class BlockStatementTest extends ObjectTestCase
{
    public static function className(): string
    {
        return BlockStatement::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'codeList' => ['$ref' => '#/components/schemas/CodeList-post'],
            ],
            'required' => ['codeList'],
        ];
    }

    #[Test]
    public function block_statement_renders_braced_code(): void
    {
        $block = new BlockStatement(new CodeList([
            new RawJavascript('doThing();'),
            new VariableAssignment(
                VariableDeclarationKind::Let,
                new JavascriptIdentifier('value'),
                new RawJavascript('12'),
                null,
            ),
        ]));

        $this->assertSame("{\n    doThing();\n    let value = 12;\n}", $block->toTypescript());
    }
}
