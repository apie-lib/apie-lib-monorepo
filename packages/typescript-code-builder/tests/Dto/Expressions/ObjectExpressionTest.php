<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\Expressions;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\Expressions\NumberLiteralExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\ObjectExpression;
use Apie\TypescriptCodeBuilder\Lists\TypescriptExpressionHashmap;

class ObjectExpressionTest extends ObjectTestCase
{
    public static function className(): string
    {
        return ObjectExpression::class;
    }
    public static function getOpenApiSchemaForCreation(): array
    {
        return ['type' => 'object', 'properties' => ['properties' => ['$ref' => '#/components/schemas/TypescriptExpressionHashmap-post']], 'required' => ['properties']];
    }
    public function test_renders_all_properties(): void
    {
        $this->assertSame('{ first: 1, second: 2 }', (new ObjectExpression(new TypescriptExpressionHashmap(['first' => new NumberLiteralExpression('1'), 'second' => new NumberLiteralExpression('2')])))->toTypescript());
    }
}
