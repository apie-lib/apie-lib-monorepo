<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\Expressions;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\Expressions\UndefinedExpression;

class UndefinedExpressionTest extends ObjectTestCase
{
    public static function className(): string
    {
        return UndefinedExpression::class;
    }
    public static function getOpenApiSchemaForCreation(): array
    {
        return ['type' => 'object', 'properties' => []];
    }

    public function test_renders_undefined_literal(): void
    {
        $this->assertSame('undefined', (new UndefinedExpression())->toTypescript());
        $this->assertSame('undefined', (new UndefinedExpression())->toJavascript());
    }
}
