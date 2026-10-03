<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\Expressions;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\Expressions\IdentifierExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\PropertyAccessExpression;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;

class PropertyAccessExpressionTest extends ObjectTestCase
{
    public static function className(): string
    {
        return PropertyAccessExpression::class;
    }
    public static function getOpenApiSchemaForCreation(): array
    {
        return ['type' => 'object', 'properties' => ['object' => ['$ref' => '#/components/schemas/TypescriptFileExpression-post'], 'property' => ['$ref' => '#/components/schemas/JavascriptIdentifier-post']], 'required' => ['object', 'property']];
    }
    public function test_renders_property_access(): void
    {
        $this->assertSame('user.name', (new PropertyAccessExpression(new IdentifierExpression(new JavascriptIdentifier('user')), new JavascriptIdentifier('name')))->toTypescript());
    }
}
