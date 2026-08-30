<?php
namespace Apie\Tests\CountryAndPhoneNumber;

use Apie\CountryAndPhoneNumber\BritishPhoneNumber;
use Apie\Fixtures\TestHelpers\ValueObjectTestCase;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class BritishPhoneNumberTest extends ValueObjectTestCase
{
    public static function className(): string
    {
        return BritishPhoneNumber::class;
    }

    public static function provideFromNative(): array
    {
        $util = PhoneNumberUtil::getInstance();
        $example = $util->getExampleNumber('GB');
        $value = $example ? $util->format($example, PhoneNumberFormat::E164) : '+447700900123';

        return [
            'valid british phone number' => [$value, $value],
        ];
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'string',
            'format' => 'britishphonenumber',
            'description' => true,
        ];
    }
}
