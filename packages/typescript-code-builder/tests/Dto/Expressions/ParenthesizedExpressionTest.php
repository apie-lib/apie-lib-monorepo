<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\Expressions;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\Expressions\NumberLiteralExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\ParenthesizedExpression;

class ParenthesizedExpressionTest extends ObjectTestCase
{
    public static function className(): string
    {
        return ParenthesizedExpression::class;
    }
    public static function getOpenApiSchemaForCreation(): array
    {
        return ['type' => 'object', 'properties' => ['expression' => ['$ref' => '#/components/schemas/TypescriptFileExpression-post']], 'required' => ['expression']];
    }
    public function test_renders_parentheses(): void
    {
        $this->assertSame('(1)', (new ParenthesizedExpression(new NumberLiteralExpression('1')))->toTypescript());
    }
}
