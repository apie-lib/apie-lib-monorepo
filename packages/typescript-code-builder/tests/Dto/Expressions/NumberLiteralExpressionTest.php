<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\Expressions;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\Expressions\NumberLiteralExpression;

class NumberLiteralExpressionTest extends ObjectTestCase
{
    public static function className(): string
    {
        return NumberLiteralExpression::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return ['type' => 'string', 'format' => 'numberliteralexpression', 'pattern' => true];
    }
    public function test_renders_decimal_octal_and_hexadecimal_literals(): void
    {
        $this->assertSame('12', (new NumberLiteralExpression('12'))->toTypescript());
        $this->assertSame('0o12', (new NumberLiteralExpression('0o12'))->toTypescript());
        $this->assertSame('0xFF', (new NumberLiteralExpression('0xFF'))->toTypescript());
    }
}
