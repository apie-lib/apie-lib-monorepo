<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\RawJavascript;

class RawJavascriptTest extends ObjectTestCase
{
    public static function className(): string
    {
        return RawJavascript::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'required' => ['javascriptCode'],
            'properties' => [
                'javascriptCode' => [
                    'type' => 'string',
                    'nullable' => false,
                ],
                'typescriptCode' => [
                    'type' => 'string',
                    'nullable' => true,
                ],
                'providesDefinition' => [
                    '$ref' => '#/components/schemas/JavascriptIdentifierList-post'
                ],
                'needsDefinition' => [
                    '$ref' => '#/components/schemas/JavascriptIdentifierList-post'
                ],
            ]
        ];
    }
}
