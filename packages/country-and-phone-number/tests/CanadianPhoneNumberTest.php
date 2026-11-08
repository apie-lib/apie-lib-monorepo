<?php
namespace Apie\Tests\CountryAndPhoneNumber;

use Apie\CountryAndPhoneNumber\CanadianPhoneNumber;
use Apie\Fixtures\TestHelpers\ValueObjectTestCase;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class CanadianPhoneNumberTest extends ValueObjectTestCase
{
    public static function className(): string
    {
        return CanadianPhoneNumber::class;
    }

    public static function provideFromNative(): array
    {
        $util = PhoneNumberUtil::getInstance();
        $example = $util->getExampleNumber('CA');
        $value = $example ? $util->format($example, PhoneNumberFormat::E164) : '+14165550123';

        return [
            'valid canadian phone number' => [$value, $value],
        ];
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'string',
            'format' => 'canadianphonenumber',
            'description' => true,
        ];
    }
}
