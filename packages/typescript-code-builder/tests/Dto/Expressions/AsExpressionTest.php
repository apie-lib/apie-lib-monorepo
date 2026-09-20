<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\Expressions;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\Expressions\AsExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\IdentifierExpression;
use Apie\TypescriptCodeBuilder\Enums\TypescriptType;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;

class AsExpressionTest extends ObjectTestCase
{
    public static function className(): string
    {
        return AsExpression::class;
    }
    public static function getOpenApiSchemaForCreation(): array
    {
        return ['type' => 'object', 'properties' => ['expression' => ['$ref' => '#/components/schemas/TypescriptFileExpression-post'], 'type' => ['$ref' => '#/components/schemas/TypescriptTypeDeclaration-post']], 'required' => ['expression', 'type']];
    }
    public function test_renders_as_operator(): void
    {
        $this->assertSame('value as string', (new AsExpression(new IdentifierExpression(new JavascriptIdentifier('value')), TypescriptType::String))->toTypescript());
    }
}
