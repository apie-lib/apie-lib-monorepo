<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\Expressions;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\Expressions\NumberLiteralExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\UnaryOperationExpression;
use Apie\TypescriptCodeBuilder\Enums\UnaryOperator;

class UnaryOperationExpressionTest extends ObjectTestCase
{
    public static function className(): string
    {
        return UnaryOperationExpression::class;
    }
    public static function getOpenApiSchemaForCreation(): array
    {
        return ['type' => 'object', 'properties' => ['operator' => ['$ref' => '#/components/schemas/UnaryOperator-post'], 'expression' => ['$ref' => '#/components/schemas/TypescriptFileExpression-post']], 'required' => ['operator', 'expression']];
    }
    public function test_renders_unary_operator(): void
    {
        $this->assertSame('! 1', (new UnaryOperationExpression(UnaryOperator::Not, new NumberLiteralExpression('1')))->toTypescript());
    }
}
