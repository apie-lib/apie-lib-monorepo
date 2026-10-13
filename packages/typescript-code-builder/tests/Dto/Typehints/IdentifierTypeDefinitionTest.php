<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\Typehints;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\Typehints\IdentifierTypeDefinition;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;
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
                    '$ref' => '#/components/schemas/JavascriptIdentifier-post'
                ],
            ],
            'required' => ['name'],
        ];
    }

    #[Test]
    public function type_definitions_render_no_javascript_but_render_typescript()
    {
        $testItem = new IdentifierTypeDefinition(new JavascriptIdentifier('user'));
        $this->assertEquals('', $testItem->toJavascript());
        $this->assertEquals('user', $testItem->toTypescript());
    }
}
