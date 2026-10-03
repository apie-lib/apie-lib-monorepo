<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\ControlFlow;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\ControlFlow\LabelStatement;
use PHPUnit\Framework\Attributes\Test;

class LabelStatementTest extends ObjectTestCase
{
    public static function className(): string
    {
        return LabelStatement::class;
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
    public function label_statement_renders_label(): void
    {
        $this->assertSame('done:', (new LabelStatement('done'))->toTypescript());
    }
}
