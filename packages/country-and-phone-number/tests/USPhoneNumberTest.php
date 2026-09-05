<?php
namespace Apie\Tests\CountryAndPhoneNumber;

use Apie\CountryAndPhoneNumber\USPhoneNumber;
use Apie\Fixtures\TestHelpers\ValueObjectTestCase;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class USPhoneNumberTest extends ValueObjectTestCase
{
    public static function className(): string
    {
        return USPhoneNumber::class;
    }

    public static function provideFromNative(): array
    {
        $util = PhoneNumberUtil::getInstance();
        $example = $util->getExampleNumber('US');
        $value = $example ? $util->format($example, PhoneNumberFormat::E164) : '+12025550123';

        return [
            'valid us phone number' => [$value, $value],
        ];
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'string',
            'format' => 'usphonenumber',
            'description' => true,
        ];
    }
}
