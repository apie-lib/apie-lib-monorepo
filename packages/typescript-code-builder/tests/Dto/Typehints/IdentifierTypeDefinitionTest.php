<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\Typehints;

use Apie\Core\Identifiers\Identifier;
use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\Typehints\IdentifierTypeDefinition;
use PHPUnit\Framework\Attributes\Test;

class IdentifierTypeDefinitionTest extends ObjectTestCase
{
    public static function className(): string
    {
        return IdentifierTypeDefinition::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'name' => [
                    '$ref' => '#/components/schemas/Identifier-post'
                ],
            ],
            'required' => ['name'],
        ];
    }

    #[Test]
    public function type_definitions_render_no_javascript_but_render_typescript()
    {
        $testItem = new IdentifierTypeDefinition(new Identifier('User'));
        $this->assertEquals('', $testItem->toJavascript());
        $this->assertEquals('User', $testItem->toTypescript());
    }
}
