<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\Expressions;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\Expressions\FunctionCallExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\IdentifierExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\NumberLiteralExpression;
use Apie\TypescriptCodeBuilder\Lists\ExpressionList;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;

class FunctionCallExpressionTest extends ObjectTestCase
{
    public static function className(): string
    {
        return FunctionCallExpression::class;
    }
    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'function' => ['$ref' => '#/components/schemas/TypescriptFileExpression-post'],
                'arguments' => ['$ref' => '#/components/schemas/ExpressionList-post']
            ],
            'required' => ['function']
        ];
    }
    public function test_renders_function_call(): void
    {
        $this->assertSame('sum(1)', (new FunctionCallExpression(new IdentifierExpression(new JavascriptIdentifier('sum')), new ExpressionList([new NumberLiteralExpression('1')])))->toTypescript());
    }
}
