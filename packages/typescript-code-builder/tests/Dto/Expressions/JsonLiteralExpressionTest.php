<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\Expressions;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\Expressions\JsonLiteralExpression;

class JsonLiteralExpressionTest extends ObjectTestCase
{
    public static function className(): string
    {
        return JsonLiteralExpression::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'value' => ['$ref' => '#/components/schemas/mixed'],
                'prettified' => ['type' => 'boolean', 'nullable' => false],
            ],
            'required' => ['value'],
        ];
    }

    public function test_renders_compact_and_prettified_json(): void
    {
        $value = ['name' => 'Alice', 'path' => 'a/b'];

        $this->assertSame('JSON.parse("{\"name\":\"Alice\",\"path\":\"a/b\"}")', (new JsonLiteralExpression($value))->toTypescript());
        $this->assertSame("{\n    \"name\": \"Alice\",\n    \"path\": \"a/b\"\n}", (new JsonLiteralExpression($value, true))->toTypescript());
        $this->assertSame('JSON.parse("{\"name\":\"Alice\",\"path\":\"a/b\"}")', (new JsonLiteralExpression($value))->toJavascript());
        $this->assertSame("{\n    \"name\": \"Alice\",\n    \"path\": \"a/b\"\n}", (new JsonLiteralExpression($value, true))->toJavascript());
    }
}
