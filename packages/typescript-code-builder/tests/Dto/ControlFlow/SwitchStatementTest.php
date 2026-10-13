<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\ControlFlow;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\ControlFlow\BreakStatement;
use Apie\TypescriptCodeBuilder\Dto\ControlFlow\SwitchCase;
use Apie\TypescriptCodeBuilder\Dto\ControlFlow\SwitchStatement;
use Apie\TypescriptCodeBuilder\Dto\RawJavascript;
use Apie\TypescriptCodeBuilder\Lists\CodeList;
use Apie\TypescriptCodeBuilder\Lists\SwitchCaseList;
use PHPUnit\Framework\Attributes\Test;

class SwitchStatementTest extends ObjectTestCase
{
    public static function className(): string
    {
        return SwitchStatement::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'expression' => [
                    '$ref' => '#/components/schemas/TypescriptFileExpression-post'
                ],
                'cases' => [
                    '$ref' => '#/components/schemas/SwitchCaseList-post'
                ],
            ],
            'required' => ['expression', 'cases'],
        ];
    }

    #[Test]
    public function switch_statement_renders_case_and_default_output(): void
    {
        $statement = new SwitchStatement(
            new RawJavascript('state'),
            new SwitchCaseList(
                [
                    new SwitchCase(new RawJavascript("'ready'"), new CodeList([
                        new RawJavascript('run();'),
                        new BreakStatement(),
                    ])),
                    new SwitchCase(null, new CodeList([
                        new RawJavascript('fallback();'),
                    ])),
                ],
            )
        );

        $this->assertSame("switch (state) {\n    case 'ready':\n        run();\n        break;\n    default:\n        fallback();\n}", $statement->toTypescript());
    }
}
