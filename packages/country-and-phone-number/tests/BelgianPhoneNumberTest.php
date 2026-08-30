<?php
namespace Apie\Tests\CountryAndPhoneNumber;

use Apie\CountryAndPhoneNumber\BelgianPhoneNumber;
use Apie\Fixtures\TestHelpers\ValueObjectTestCase;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class BelgianPhoneNumberTest extends ValueObjectTestCase
{
    public static function className(): string
    {
        return BelgianPhoneNumber::class;
    }

    public static function provideFromNative(): array
    {
        $util = PhoneNumberUtil::getInstance();
        $example = $util->getExampleNumber('BE');
        $value = $example ? $util->format($example, PhoneNumberFormat::E164) : '+32471123456';

        return [
            'valid belgian phone number' => [$value, $value],
        ];
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'string',
            'format' => 'belgianphonenumber',
            'description' => true,
        ];
    }
}
