<?php
namespace Apie\Tests\CountryAndPhoneNumber;

use Apie\CountryAndPhoneNumber\ChinesePhoneNumber;
use Apie\Fixtures\TestHelpers\ValueObjectTestCase;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class ChinesePhoneNumberTest extends ValueObjectTestCase
{
    public static function className(): string
    {
        return ChinesePhoneNumber::class;
    }

    public static function provideFromNative(): array
    {
        $util = PhoneNumberUtil::getInstance();
        $example = $util->getExampleNumber('CN');
        $value = $example ? $util->format($example, PhoneNumberFormat::E164) : '+8613800138000';

        return [
            'valid chinese phone number' => [$value, $value],
        ];
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'string',
            'format' => 'chinesephonenumber',
            'description' => true,
        ];
    }
}
