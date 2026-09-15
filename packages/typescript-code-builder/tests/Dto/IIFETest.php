<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto;

use Apie\Core\Identifiers\Identifier;
use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\IIFE;
use Apie\TypescriptCodeBuilder\Dto\TypescriptDeclaration;
use Apie\TypescriptCodeBuilder\Enums\TypescriptType;
use Apie\TypescriptCodeBuilder\Lists\CodeList;
use PHPUnit\Framework\Attributes\Test;

class IIFEtest extends ObjectTestCase
{
    public static function className(): string
    {
        return IIFE::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'codeList' => [
                    '$ref' => '#/components/schemas/CodeList-post',
                ]
            ],
            'required' => ['codeList'],
        ];
    }

    #[Test]
    public function type_definitions_renders_only_typescript()
    {
        $testItem = new IIFE(
            new CodeList([
                new TypescriptDeclaration(Identifier::fromNative('example'), TypescriptType::Boolean)
            ])
        );
        $this->assertEquals('(function(){

}());', $testItem->toJavascript());
        $this->assertEquals('(function(){
    type example = boolean;
}());', $testItem->toTypescript());
        $this->assertEquals([], $testItem->providesDefinitions()->toArray());
        $this->assertEquals([], $testItem->needsDefinitions()->toArray());
    }
}
