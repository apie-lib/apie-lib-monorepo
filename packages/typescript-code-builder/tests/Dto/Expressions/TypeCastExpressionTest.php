<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\Expressions;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\Expressions\IdentifierExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\TypeCastExpression;
use Apie\TypescriptCodeBuilder\Enums\TypescriptType;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;

class TypeCastExpressionTest extends ObjectTestCase
{
    public static function className(): string
    {
        return TypeCastExpression::class;
    }
    public static function getOpenApiSchemaForCreation(): array
    {
        return ['type' => 'object', 'properties' => ['type' => ['$ref' => '#/components/schemas/TypescriptTypeDeclaration-post'], 'expression' => ['$ref' => '#/components/schemas/TypescriptFileExpression-post']], 'required' => ['type', 'expression']];
    }
    public function test_renders_typescript_cast(): void
    {
        $this->assertSame('<any>value', (new TypeCastExpression(TypescriptType::Any, new IdentifierExpression(new JavascriptIdentifier('value'))))->toTypescript());
    }
}
