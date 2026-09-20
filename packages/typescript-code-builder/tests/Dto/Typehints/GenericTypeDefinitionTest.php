<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\Typehints;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\Typehints\GenericTypeDefinition;
use Apie\TypescriptCodeBuilder\Enums\TypescriptType;
use Apie\TypescriptCodeBuilder\Lists\TypescriptDeclarationList;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;
use PHPUnit\Framework\Attributes\Test;

class GenericTypeDefinitionTest extends ObjectTestCase
{
    public static function className(): string
    {
        return GenericTypeDefinition::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'name' => ['$ref' => '#/components/schemas/JavascriptIdentifier-post'],
                'typeArguments' => ['$ref' => '#/components/schemas/TypescriptDeclarationList-post'],
            ],
            'required' => ['name', 'typeArguments'],
        ];
    }

    #[Test]
    public function generic_types_render_type_arguments(): void
    {
        $type = new GenericTypeDefinition(
            new JavascriptIdentifier('Promise'),
            new TypescriptDeclarationList([TypescriptType::String]),
        );

        $this->assertSame('Promise<string>', $type->toTypescript());
        $this->assertSame('', $type->toJavascript());
    }
}
