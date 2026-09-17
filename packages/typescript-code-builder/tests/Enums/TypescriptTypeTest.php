<?php
namespace Apie\Tests\TypescriptCodeBuilder\Enums;

use Apie\Fixtures\TestHelpers\ObjectTestCase;
use Apie\TypescriptCodeBuilder\Enums\TypescriptType;

class TypescriptTypeTest extends ObjectTestCase
{
    public static function className(): string
    {
        return TypescriptType::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'string',
            'enum' => [
                'string',
                'number',
                'boolean',
                'any',
                'unknown',
                'void'
            ],
        ];
    }

}
