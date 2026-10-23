<?php
namespace Apie\Tests\CommonValueObjects;

use Apie\CommonValueObjects\Hostname;
use Apie\Fixtures\TestHelpers\ValueObjectTestCase;

class HostnameTest extends ValueObjectTestCase
{
    public static function className(): string
    {
        return Hostname::class;
    }

    
    public static function createExampleObject(): object
    {
        return new Hostname('test.nl');
    }

    public static function provideFromNative(): array
    {
        return [
            'simple case' => ['test.nl', 'test.nl'],
             'co.uk' => ['example.co.uk', 'example.co.uk'],
            'hostname is case insensitive' => ['example.com', 'EXAMPLE.COM'],
        ];
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'string',
            'format' => 'hostname',
            'description' => true,
            'example' => true,
        ];
    }
}
