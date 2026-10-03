<?php
namespace Apie\Tests\TypescriptCodeBuilder\Lists;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\Expressions\IdentifierExpression;
use Apie\TypescriptCodeBuilder\Lists\ExpressionList;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;

class ExpressionListTest extends ObjectTestCase
{
    public static function className(): string
    {
        return ExpressionList::class;
    }
    public static function getOpenApiSchemaForCreation(): array
    {
        return ['type' => 'array', 'items' => ['$ref' => '#/components/schemas/TypescriptFileExpression-post']];
    }
    public function test_renders_expressions(): void
    {
        $list = new ExpressionList([new IdentifierExpression(new JavascriptIdentifier('value'))]);
        $this->assertSame('value', $list->toTypescript());
    }
}
