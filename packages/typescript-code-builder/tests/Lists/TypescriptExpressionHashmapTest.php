<?php
namespace Apie\Tests\TypescriptCodeBuilder\Lists;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\Expressions\NumberLiteralExpression;
use Apie\TypescriptCodeBuilder\Lists\TypescriptExpressionHashmap;

class TypescriptExpressionHashmapTest extends ObjectTestCase
{
    public static function className(): string
    {
        return TypescriptExpressionHashmap::class;
    }
    public static function getOpenApiSchemaForCreation(): array
    {
        return ['type' => 'object', 'additionalProperties' => ['$ref' => '#/components/schemas/TypescriptFileExpression-post']];
    }
    public function test_returns_typed_expressions(): void
    {
        $map = new TypescriptExpressionHashmap(['value' => new NumberLiteralExpression('1')]);
        $this->assertSame('1', $map['value']->toTypescript());
    }
}
