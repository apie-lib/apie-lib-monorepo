<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\Typehints;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\Typehints\UndefinedTypeDefinition;
use PHPUnit\Framework\Attributes\Test;

class UndefinedTypeDefinitionTest extends ObjectTestCase
{
    public static function className(): string
    {
        return UndefinedTypeDefinition::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'properties' => [],
        ];
    }

    #[Test]
    public function undefined_types_render_as_undefined(): void
    {
        $type = new UndefinedTypeDefinition();

        $this->assertEquals('undefined', $type->toTypescript());
        $this->assertEquals('', $type->toJavascript());
        $this->assertCount(0, $type->needsDefinitions());
    }
}
