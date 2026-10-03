<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\Typehints;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\Typehints\TypeGuardDefinition;
use Apie\TypescriptCodeBuilder\Enums\TypescriptType;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;
use PHPUnit\Framework\Attributes\Test;

class TypeGuardDefinitionTest extends ObjectTestCase
{
    public static function className(): string
    {
        return TypeGuardDefinition::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'value' => ['$ref' => '#/components/schemas/JavascriptIdentifier-post'],
                'type' => ['$ref' => '#/components/schemas/TypescriptTypeDeclaration-post'],
            ],
            'required' => ['value', 'type'],
        ];
    }

    #[Test]
    public function type_guards_render_typescript_only(): void
    {
        $type = new TypeGuardDefinition(new JavascriptIdentifier('value'), TypescriptType::Unknown);

        $this->assertSame('value is unknown', $type->toTypescript());
        $this->assertSame('', $type->toJavascript());
        $this->assertCount(0, $type->needsDefinitions());
    }
}
