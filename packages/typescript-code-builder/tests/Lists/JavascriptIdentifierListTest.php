<?php
namespace Apie\Tests\TypescriptCodeBuilder\Lists;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Lists\JavascriptIdentifierList;

class JavascriptIdentifierListTest extends ObjectTestCase
{
    public static function className(): string
    {
        return JavascriptIdentifierList::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'array',
            'items' => [
                '$ref' => '#/components/schemas/JavascriptIdentifier-post'
            ],
        ];
    }

    public function test_it_converts_strings_to_javascript_identifiers(): void
    {
        $list = new JavascriptIdentifierList(['validName', '_privateValue']);

        $this->assertSame(['validName', '_privateValue'], $list->toStringArray());
        $this->assertSame('validName', $list[0]->toNative());
    }
}