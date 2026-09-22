<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\Expressions;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\Expressions\BooleanLiteralExpression;

class BooleanLiteralExpressionTest extends ObjectTestCase
{
    public static function className(): string
    {
        return BooleanLiteralExpression::class;
    }
    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'value' => [
                    'type' => 'boolean',
                    'nullable' => false,
                ]
            ],
            'required' => ['value']
        ];
    }

    public function test_renders_true_and_false_literals(): void
    {
        $this->assertSame('true', (new BooleanLiteralExpression(true))->toTypescript());
        $this->assertSame('false', (new BooleanLiteralExpression(false))->toTypescript());
        $this->assertSame('true', (new BooleanLiteralExpression(true))->toJavascript());
        $this->assertSame('false', (new BooleanLiteralExpression(false))->toJavascript());
    }
}
