<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\Expressions;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\Expressions\NullExpression;

class NullExpressionTest extends ObjectTestCase
{
    public static function className(): string
    {
        return NullExpression::class;
    }
    public static function getOpenApiSchemaForCreation(): array
    {
        return ['type' => 'object', 'properties' => []];
    }

    public function test_renders_null_literal(): void
    {
        $this->assertSame('null', (new NullExpression())->toTypescript());
        $this->assertSame('null', (new NullExpression())->toJavascript());
    }
}
