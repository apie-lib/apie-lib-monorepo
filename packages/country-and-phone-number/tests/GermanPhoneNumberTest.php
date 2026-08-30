<?php
namespace Apie\Tests\CountryAndPhoneNumber;

use Apie\CountryAndPhoneNumber\GermanPhoneNumber;
use Apie\Fixtures\TestHelpers\ValueObjectTestCase;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class GermanPhoneNumberTest extends ValueObjectTestCase
{
    public static function className(): string
    {
        return GermanPhoneNumber::class;
    }

    public static function provideFromNative(): array
    {
        $util = PhoneNumberUtil::getInstance();
        $example = $util->getExampleNumber('DE');
        $value = $example ? $util->format($example, PhoneNumberFormat::E164) : '+491701234567';

        return [
            'valid german phone number' => [$value, $value],
        ];
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'string',
            'format' => 'germanphonenumber',
            'description' => true,
        ];
    }
}
