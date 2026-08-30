<?php
namespace Apie\Tests\CountryAndPhoneNumber;

use Apie\CountryAndPhoneNumber\JapanesePhoneNumber;
use Apie\Fixtures\TestHelpers\ValueObjectTestCase;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class JapanesePhoneNumberTest extends ValueObjectTestCase
{
    public static function className(): string
    {
        return JapanesePhoneNumber::class;
    }

    public static function provideFromNative(): array
    {
        $util = PhoneNumberUtil::getInstance();
        $example = $util->getExampleNumber('JP');
        $value = $example ? $util->format($example, PhoneNumberFormat::E164) : '+81312345678';

        return [
            'valid japanese phone number' => [$value, $value],
        ];
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'string',
            'format' => 'japanesephonenumber',
            'description' => true,
        ];
    }
}
