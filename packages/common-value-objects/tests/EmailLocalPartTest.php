<?php
namespace Apie\Tests\CommonValueObjects;

use Apie\CommonValueObjects\EmailLocalPart;
use Apie\Fixtures\TestHelpers\ValueObjectTestCase;

class EmailLocalPartTest extends ValueObjectTestCase
{
    public static function className(): string
    {
        return EmailLocalPart::class;
    }
    
    public static function createExampleObject(): object
    {
        return EmailLocalPart::fromNative('test');
    }
    public static function provideFromNative(): array
    {
        return [
            'simple case' => ['test', 'test'],
             'with + alias' => ['test+example', 'test+example'],
        ];
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'string',
            'format' => 'emaillocalpart',
            'description' => true,
        ];
    }
}
