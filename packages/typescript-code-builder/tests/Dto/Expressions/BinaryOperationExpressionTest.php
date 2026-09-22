<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\Expressions;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\Expressions\BinaryOperationExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\NumberLiteralExpression;
use Apie\TypescriptCodeBuilder\Enums\BinaryOperator;

class BinaryOperationExpressionTest extends ObjectTestCase
{
    public static function className(): string
    {
        return BinaryOperationExpression::class;
    }
    public static function getOpenApiSchemaForCreation(): array
    {
        return ['type' => 'object', 'properties' => ['left' => ['$ref' => '#/components/schemas/TypescriptFileExpression-post'], 'operator' => ['$ref' => '#/components/schemas/BinaryOperator-post'], 'right' => ['$ref' => '#/components/schemas/TypescriptFileExpression-post']], 'required' => ['left', 'operator', 'right']];
    }
    public function test_renders_binary_operator(): void
    {
        $this->assertSame('1 + 2', (new BinaryOperationExpression(new NumberLiteralExpression('1'), BinaryOperator::Add, new NumberLiteralExpression('2')))->toTypescript());
    }
}
