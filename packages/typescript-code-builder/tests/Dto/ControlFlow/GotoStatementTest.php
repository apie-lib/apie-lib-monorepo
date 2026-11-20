<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\ControlFlow;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\ControlFlow\GotoStatement;
use PHPUnit\Framework\Attributes\Test;

class GotoStatementTest extends ObjectTestCase
{
    public static function className(): string
    {
        return GotoStatement::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'label' => ['type' => 'string', 'nullable' => false],
            ],
            'required' => ['label'],
        ];
    }

    #[Test]
    public function goto_statement_renders_goto_label(): void
    {
        $this->assertSame('goto done;', (new GotoStatement('done'))->toTypescript());
    }
}
