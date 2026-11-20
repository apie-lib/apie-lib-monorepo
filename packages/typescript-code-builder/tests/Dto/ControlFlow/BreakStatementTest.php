<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\ControlFlow;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\ControlFlow\BreakStatement;
use PHPUnit\Framework\Attributes\Test;

class BreakStatementTest extends ObjectTestCase
{
    public static function className(): string
    {
        return BreakStatement::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'label' => ['type' => 'string', 'nullable' => true],
            ],
        ];
    }

    #[Test]
    public function break_statement_renders_break(): void
    {
        $this->assertSame('break;', (new BreakStatement())->toTypescript());
    }
}
