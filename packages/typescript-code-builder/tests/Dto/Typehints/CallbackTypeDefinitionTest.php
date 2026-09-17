<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto\Typehints;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\FunctionArgument;
use Apie\TypescriptCodeBuilder\Dto\Typehints\CallbackTypeDefinition;
use Apie\TypescriptCodeBuilder\Dto\Typehints\IdentifierTypeDefinition;
use Apie\TypescriptCodeBuilder\Enums\TypescriptType;
use Apie\TypescriptCodeBuilder\Lists\ArgumentList;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;
use PHPUnit\Framework\Attributes\Test;

class CallbackTypeDefinitionTest extends ObjectTestCase
{
    public static function className(): string
    {
        return CallbackTypeDefinition::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'arguments' => ['$ref' => '#/components/schemas/ArgumentList-post'],
                'returnType' => ['$ref' => '#/components/schemas/TypescriptTypeDeclaration-post'],
            ],
            'required' => ['arguments', 'returnType'],
        ];
    }

    #[Test]
    public function callback_types_render_and_collect_nested_dependencies(): void
    {
        $user = new JavascriptIdentifier('user');
        $type = new CallbackTypeDefinition(
            new ArgumentList([
                new FunctionArgument(new JavascriptIdentifier('u'), new IdentifierTypeDefinition($user)),
            ]),
            TypescriptType::Boolean,
        );

        $this->assertEquals('(u: user) => boolean', $type->toTypescript());
        $this->assertEquals('', $type->toJavascript());
        $this->assertEquals([$user], $type->needsDefinitions()->toArray());
    }
}