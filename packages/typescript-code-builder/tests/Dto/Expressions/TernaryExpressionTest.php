<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\Expressions;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\Expressions\IdentifierExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\NumberLiteralExpression;
use Apie\TypescriptCodeBuilder\Dto\Expressions\TernaryExpression;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;
use PHPUnit\Framework\Attributes\Test;

class TernaryExpressionTest extends ObjectTestCase
{
    public static function className(): string
    {
        return TernaryExpression::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'condition' => ['$ref' => '#/components/schemas/TypescriptFileExpression-post'],
                'whenTrue' => ['$ref' => '#/components/schemas/TypescriptFileExpression-post'],
                'whenFalse' => ['$ref' => '#/components/schemas/TypescriptFileExpression-post'],
            ],
            'required' => ['condition', 'whenTrue', 'whenFalse'],
        ];
    }

    #[Test]
    public function ternary_expressions_render_both_languages(): void
    {
        $type = new TernaryExpression(
            new IdentifierExpression(new JavascriptIdentifier('enabled')),
            new NumberLiteralExpression('1'),
            new NumberLiteralExpression('0'),
        );

        $this->assertSame('enabled ? 1 : 0', $type->toTypescript());
        $this->assertSame('enabled ? 1 : 0', $type->toJavascript());
        $this->assertSame(['enabled'], $type->needsDefinitions()->toStringArray());
    }
}
