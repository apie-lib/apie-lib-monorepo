<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto;

use Apie\Core\Identifiers\Identifier;
use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\FunctionArgument;
use Apie\TypescriptCodeBuilder\Dto\IIFE;
use Apie\TypescriptCodeBuilder\Dto\NamedFunction;
use Apie\TypescriptCodeBuilder\Dto\RawJavascript;
use Apie\TypescriptCodeBuilder\Dto\TypescriptDeclaration;
use Apie\TypescriptCodeBuilder\Enums\TypescriptType;
use Apie\TypescriptCodeBuilder\Lists\ArgumentList;
use Apie\TypescriptCodeBuilder\Lists\CodeList;
use PHPUnit\Framework\Attributes\Test;

class NamedFunctionTest extends ObjectTestCase
{
    public static function className(): string
    {
        return NamedFunction::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'name' => [
                    '$ref' => '#/components/schemas/Identifier-post',
                ],
                'arguments' => [
                    '$ref' => '#/components/schemas/ArgumentList-post',
                ],
                'codeList' => [
                    '$ref' => '#/components/schemas/CodeList-post',
                ]
            ],
            'required' => [
                'name',
                'arguments',
                'codeList',
            ],
        ];
    }

    #[Test]
    public function type_definitions_renders_only_typescript()
    {
        $testItem = new NamedFunction(
            new Identifier('add'),
            new ArgumentList([
                new FunctionArgument(new Identifier('a'), TypescriptType::Number),
                new FunctionArgument(new Identifier('b'), TypescriptType::Number, true)
            ]),
            new CodeList([
                new RawJavascript('return a + (b ?? 0)')
            ])
        );
        $this->assertEquals('function add(a, b) {
    return a + (b ?? 0)
}', $testItem->toJavascript());
        $this->assertEquals('function add(a: number, b?: number) {
    return a + (b ?? 0)
}', $testItem->toTypescript());
        $this->assertEquals([new Identifier('add')], $testItem->providesDefinitions()->toArray());
        $this->assertEquals([], $testItem->needsDefinitions()->toArray());
    }
}
