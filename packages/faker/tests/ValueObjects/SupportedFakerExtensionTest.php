<?php
namespace Apie\Tests\Faker\ValueObjects;

use Apie\Faker\ValueObjects\SupportedFakerExtension;
use Apie\Fixtures\TestHelpers\ObjectTestCase;

class SupportedFakerExtensionTest extends ObjectTestCase
{
    public static function className(): string
    {
        return SupportedFakerExtension::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'string',
            'format' => 'supportedfakerextension',
            'description' => true,
            'pattern' => true
        ];
    }
}
