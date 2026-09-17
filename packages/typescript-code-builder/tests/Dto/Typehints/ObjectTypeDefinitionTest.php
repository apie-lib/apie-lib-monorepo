<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\Typehints;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\FunctionArgument;
use Apie\TypescriptCodeBuilder\Dto\Typehints\ObjectTypeDefinition;
use Apie\TypescriptCodeBuilder\Enums\TypescriptType;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;
use Apie\TypescriptCodeBuilder\Lists\ArgumentList;
use PHPUnit\Framework\Attributes\Test;

class ObjectTypeDefinitionTest extends ObjectTestCase
{
    public static function className(): string
    {
        return ObjectTypeDefinition::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'properties' => [
                    '$ref' => '#/components/schemas/ArgumentList-post',
                ]
            ],
            'required' => ['properties'],
        ];
    }

    #[Test]
    public function object_types_render_properties(): void
    {
        $type = new ObjectTypeDefinition(
            new ArgumentList([
                new FunctionArgument(new JavascriptIdentifier('name'), TypescriptType::String),
                new FunctionArgument(new JavascriptIdentifier('age'), TypescriptType::Number, true),
            ])
        );

        $this->assertEquals('{ name: string; age?: number; }', $type->toTypescript());
        $this->assertEquals('', $type->toJavascript());
    }
}