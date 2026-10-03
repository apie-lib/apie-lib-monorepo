<?php
namespace Apie\Tests\CountryAndPhoneNumber;

use Apie\CountryAndPhoneNumber\FrenchPhoneNumber;
use Apie\Fixtures\TestHelpers\ValueObjectTestCase;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class FrenchPhoneNumberTest extends ValueObjectTestCase
{
    public static function className(): string
    {
        return FrenchPhoneNumber::class;
    }

    public static function provideFromNative(): array
    {
        $util = PhoneNumberUtil::getInstance();
        $example = $util->getExampleNumber('FR');
        $value = $example ? $util->format($example, PhoneNumberFormat::E164) : '+33123456789';

        return [
            'valid french phone number' => [$value, $value],
        ];
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'string',
            'format' => 'frenchphonenumber',
            'description' => true,
        ];
    }
}
