<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\Expressions;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\Expressions\StringLiteralExpression;

class StringLiteralExpressionTest extends ObjectTestCase
{
    public static function className(): string
    {
        return StringLiteralExpression::class;
    }
    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'value' => [
                    'type' => 'string',
                    'nullable' => false,
                ]
            ],
            'required' => ['value']
        ];
    }
    public function test_renders_escaped_string(): void
    {
        $this->assertSame('"hello\\""', (new StringLiteralExpression('hello"'))->toTypescript());
    }
}
