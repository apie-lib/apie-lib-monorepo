<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\File;
use Apie\TypescriptCodeBuilder\Dto\TypescriptDeclaration;
use Apie\TypescriptCodeBuilder\Enums\TypescriptType;
use Apie\TypescriptCodeBuilder\Lists\CodeList;
use Apie\TypescriptCodeBuilder\ValueObjects\JavascriptIdentifier;
use PHPUnit\Framework\Attributes\Test;

class Filetest extends ObjectTestCase
{
    public static function className(): string
    {
        return File::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'properties' => [],
        ];
    }

    #[Test]
    public function type_definitions_renders_only_typescript()
    {
        $testItem = new File(
            new CodeList([
                new TypescriptDeclaration(JavascriptIdentifier::fromNative('example'), TypescriptType::Boolean)
            ])
        );
        $this->assertEquals('', $testItem->toJavascript());
        $this->assertEquals('type example = bool', $testItem->toTypescript());
        $this->assertEquals([], $testItem->providesDefinitions(false)->toArray());
        $this->assertEquals([], $testItem->needsDefinitions()->toArray());
    }
}
