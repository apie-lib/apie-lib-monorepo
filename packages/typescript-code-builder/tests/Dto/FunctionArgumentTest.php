<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto;

use Apie\Core\Identifiers\Identifier;
use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\FunctionArgument;
use Apie\TypescriptCodeBuilder\Enums\TypescriptType;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class FunctionArgumentTest extends ObjectTestCase
{
    public static function className(): string
    {
        return FunctionArgument::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'required' => [
                'name',
            ],
            'properties' => [
                'name' => ['$ref' => '#/components/schemas/Identifier-post'],
                'typehint' => ['$ref' => '#/components/schemas/TypescriptTypeDeclaration-nullable-post'],
                'optional' => ['type' => 'boolean', 'nullable' => false],
            ],
        ];
    }

    #[Test]
    #[DataProvider('provideJavascriptCode')]
    public function type_definitions_renders_only_typescript(
        array $expectedNeededDefinitions,
        string $expectedJavascript,
        string $expectedTypescript,
        FunctionArgument $testItem,
    ) {
        $this->assertEquals($expectedJavascript, $testItem->toJavascript());
        $this->assertEquals($expectedTypescript, $testItem->toTypescript());
        $this->assertEquals([], $testItem->providesDefinitions()->toArray());
        $this->assertEquals($expectedNeededDefinitions, $testItem->needsDefinitions()->toStringArray());
    }

    public static function provideJavascriptCode(): \Generator
    {
        yield 'required function argument without typehint' => [
            [],
            'argument',
            'argument',
            new FunctionArgument(
                new Identifier('argument')
            )
        ];
        yield 'optional function argument without typehint' => [
            [],
            'argument',
            'argument?: unknown',
            new FunctionArgument(
                new Identifier('argument'),
                optional: true
            )
        ];
        yield 'required function argument with typehint' => [
            [],
            'argument',
            'argument: number',
            new FunctionArgument(
                new Identifier('argument'),
                TypescriptType::Number
            )
        ];
        yield 'optional function argument with typehint' => [
            [],
            'argument',
            'argument?: number',
            new FunctionArgument(
                new Identifier('argument'),
                TypescriptType::Number,
                true
            )
        ];
    }
}