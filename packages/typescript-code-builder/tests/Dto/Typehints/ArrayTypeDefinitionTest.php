<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\Typehints;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\Typehints\ArrayTypeDefinition;
use Apie\TypescriptCodeBuilder\Enums\TypescriptType;
use PHPUnit\Framework\Attributes\Test;

class ArrayTypeDefinitionTest extends ObjectTestCase
{
    public static function className(): string
    {
        return ArrayTypeDefinition::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'typehint' => [
                    '$ref' => '#/components/schemas/TypescriptTypeDeclaration-post'
                ],
            ],
            'required' => ['typehint'],
        ];
    }

    #[Test]
    public function type_definitions_render_no_javascript_but_render_typescript()
    {
        $testItem = new ArrayTypeDefinition(TypescriptType::String);
        $this->assertEquals('', $testItem->toJavascript());
        $this->assertEquals('string[]', $testItem->toTypescript());
    }
}
