<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\Typehints;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\FunctionArgument;
use Apie\TypescriptCodeBuilder\Dto\Typehints\InterfaceDefinition;
use Apie\TypescriptCodeBuilder\Enums\TypescriptType;
use Apie\TypescriptCodeBuilder\Lists\ArgumentList;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifierKey;
use PHPUnit\Framework\Attributes\Test;

class InterfaceDefinitionTest extends ObjectTestCase
{
    public static function className(): string
    {
        return InterfaceDefinition::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'name' => ['$ref' => '#/components/schemas/JavascriptIdentifier-post'],
                'properties' => ['$ref' => '#/components/schemas/ArgumentList-post'],
                'extends' => ['$ref' => '#/components/schemas/JavascriptIdentifierList-nullable-post'],
            ],
            'required' => ['name', 'properties'],
        ];
    }

    #[Test]
    public function interface_types_render_named_properties(): void
    {
        $type = new InterfaceDefinition(
            new JavascriptIdentifier('User'),
            new ArgumentList([
                new FunctionArgument(new JavascriptIdentifierKey('name'), TypescriptType::String),
                new FunctionArgument(new JavascriptIdentifierKey('age'), TypescriptType::Number, true),
            ]),
            new JavascriptIdentifierList([
                new JavascriptIdentifier('ExtendedInterface'),
                new JavascriptIdentifier('DebuggableInterface')
            ])
        );

        $this->assertSame('interface User extends ExtendedInterface, DebuggableInterface {
    name: string;
    age?: number;
}', $type->toTypescript());
        $this->assertSame('', $type->toJavascript());
        $this->assertSame(['User'], $type->providesDefinitions(false)->toStringArray());
    }
}
