<?php
namespace Apie\Tests\TypescriptCodeBuilder\Lists;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Lists\CodeList;

class CodeListTest extends ObjectTestCase
{
    public static function className(): string
    {
        return CodeList::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'array',
            'items' => [
                '$ref' => '#/components/schemas/TypescriptFileExpression-post'
            ],
        ];
    }
}
