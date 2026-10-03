<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\Expressions;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\Expressions\IdentifierExpression;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;

class IdentifierExpressionTest extends ObjectTestCase
{
    public static function className(): string
    {
        return IdentifierExpression::class;
    }
    public static function getOpenApiSchemaForCreation(): array
    {
        return ['type' => 'object', 'properties' => ['name' => ['$ref' => '#/components/schemas/JavascriptIdentifier-post']], 'required' => ['name']];
    }
    public function test_renders_identifier(): void
    {
        $this->assertSame('value', (new IdentifierExpression(new JavascriptIdentifier('value')))->toTypescript());
    }
}
