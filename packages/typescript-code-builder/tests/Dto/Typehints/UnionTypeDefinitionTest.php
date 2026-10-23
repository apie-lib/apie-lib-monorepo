<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\Typehints;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\Typehints\UnionTypeDefinition;
use Apie\TypescriptCodeBuilder\Enums\TypescriptType;
use Apie\TypescriptCodeBuilder\Lists\TypescriptDeclarationList;
use PHPUnit\Framework\Attributes\Test;

class UnionTypeDefinitionTest extends ObjectTestCase
{
    public static function className(): string
    {
        return UnionTypeDefinition::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'types' => ['$ref' => '#/components/schemas/TypescriptDeclarationList-post'],
            ],
            'required' => ['types'],
        ];
    }

    #[Test]
    public function union_types_render_parenthesized_types(): void
    {
        $type = new UnionTypeDefinition(
            new TypescriptDeclarationList([TypescriptType::String, TypescriptType::Number])
        );

        $this->assertEquals('(string) | (number)', $type->toTypescript());
        $this->assertEquals('', $type->toJavascript());
    }
}
