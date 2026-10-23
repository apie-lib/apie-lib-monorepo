<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\Expressions;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\Expressions\ArrayAccessExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\IdentifierExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\NumberLiteralExpression;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;

class ArrayAccessExpressionTest extends ObjectTestCase
{
    public static function className(): string
    {
        return ArrayAccessExpression::class;
    }
    public static function getOpenApiSchemaForCreation(): array
    {
        return ['type' => 'object', 'properties' => ['array' => ['$ref' => '#/components/schemas/TypescriptFileExpression-post'], 'index' => ['$ref' => '#/components/schemas/TypescriptFileExpression-post']], 'required' => ['array', 'index']];
    }
    public function test_renders_array_access(): void
    {
        $this->assertSame('items[0]', (new ArrayAccessExpression(new IdentifierExpression(new JavascriptIdentifier('items')), new NumberLiteralExpression('0')))->toTypescript());
    }
}
