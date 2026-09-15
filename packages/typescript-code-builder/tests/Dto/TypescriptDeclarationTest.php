<?php
namespace Apie\Tests\TypescriptCodeBuilder\Dto;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Dto\TypescriptDeclaration;

class TypescriptDeclarationTest extends ObjectTestCase
{
    public static function className(): string
    {
        return TypescriptDeclaration::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'name' => [
                    '$ref' => '#/components/schemas/Identifier-post'
                ],
                'typehint' => [
                    '$ref' => '#/components/schemas/TypescriptTypeDeclaration-post'
                ],
            ],
            'required' => ['name', 'typehint'],
        ];
    }
}
