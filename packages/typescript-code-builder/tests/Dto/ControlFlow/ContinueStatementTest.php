<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\ControlFlow;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\ControlFlow\ContinueStatement;
use PHPUnit\Framework\Attributes\Test;

class ContinueStatementTest extends ObjectTestCase
{
    public static function className(): string
    {
        return ContinueStatement::class;
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
    public function continue_statement_renders_continue(): void
    {
        $this->assertSame('continue;', (new ContinueStatement())->toTypescript());
    }
}
