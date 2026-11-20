<?php
namespace Apie\Tests\CountryAndPhoneNumber;

use Apie\CountryAndPhoneNumber\MexicanPhoneNumber;
use Apie\Fixtures\TestHelpers\ValueObjectTestCase;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class MexicanPhoneNumberTest extends ValueObjectTestCase
{
    public static function className(): string
    {
        return MexicanPhoneNumber::class;
    }

    public static function provideFromNative(): array
    {
        $util = PhoneNumberUtil::getInstance();
        $example = $util->getExampleNumber('MX');
        $value = $example ? $util->format($example, PhoneNumberFormat::E164) : '+525551234567';

        return [
            'valid mexican phone number' => [$value, $value],
        ];
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'string',
            'format' => 'mexicanphonenumber',
            'description' => true,
        ];
    }
}
