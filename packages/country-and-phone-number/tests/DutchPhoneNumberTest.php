<?php
namespace Apie\Tests\CountryAndPhoneNumber;

use Apie\CountryAndPhoneNumber\DutchPhoneNumber;
use Apie\Fixtures\TestHelpers\ValueObjectTestCase;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class DutchPhoneNumberTest extends ValueObjectTestCase
{
    public static function className(): string
    {
        return DutchPhoneNumber::class;
    }

    public static function provideFromNative(): array
    {
        $util = PhoneNumberUtil::getInstance();
        $example = $util->getExampleNumber('NL');
        $canonicalValue = $example ? $util->format($example, PhoneNumberFormat::E164) : '+31612345678';

        return [
            'valid dutch phone number' => [$canonicalValue, $canonicalValue],
            'valid dutch national format' => [$canonicalValue, $util->format($example, PhoneNumberFormat::NATIONAL)],
        ];
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'string',
            'format' => 'dutchphonenumber',
            'description' => true,
            'example' => true,
        ];
    }
}
