<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\Typehints;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\Typehints\LiteralTypeDefinition;
use PHPUnit\Framework\Attributes\Test;

class LiteralTypeDefinitionTest extends ObjectTestCase
{
    public static function className(): string
    {
        return LiteralTypeDefinition::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'value' => [
                    'oneOf' => [
                        ['type' => 'string'],
                        ['type' => 'integer'],
                        ['type' => 'number'],
                        ['type' => 'boolean'],
                        ['nullable' => true, 'default' => null],
                    ],
                    'nullable' => true,
                ]
            ],
            'required' => ['value'],
        ];
    }

    #[Test]
    public function literal_types_render_as_typescript_literals(): void
    {
        $this->assertEquals('"hello\\" world"', (new LiteralTypeDefinition('hello" world'))->toTypescript());
        $this->assertEquals('42', (new LiteralTypeDefinition(42))->toTypescript());
        $this->assertEquals('true', (new LiteralTypeDefinition(true))->toTypescript());
        $this->assertEquals('null', (new LiteralTypeDefinition(null))->toTypescript());
    }
}
