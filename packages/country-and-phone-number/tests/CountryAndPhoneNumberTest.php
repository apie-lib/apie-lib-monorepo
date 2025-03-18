<?php
namespace Apie\Tests\CountryAndPhoneNumber;

use Apie\CountryAndPhoneNumber\CountryAndPhoneNumber;
use Apie\CountryAndPhoneNumber\Factories\PhoneNumberFactory;
use Apie\CountryAndPhoneNumber\InternationalPhoneNumber;
use Apie\Fixtures\TestHelpers\TestValidationError;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\Fixtures\TestHelpers\TestWithOpenapiSchema;
use cebe\openapi\spec\Reference;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use PHPUnit\Framework\TestCase;
use PrinsFrank\Standards\Country\CountryAlpha2;

class CountryAndPhoneNumberTest extends TestCase
{
    use TestValidationError;
    use TestWithFaker;
    use TestWithOpenapiSchema;

    #[\PHPUnit\Framework\Attributes\DataProvider('correctProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_instantiate_correct_combinations_with_fromNative(array $expected, array $input)
    {
        $this->assertEquals($expected, CountryAndPhoneNumber::fromNative($input)->toNative());
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('correctProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_instantiate_correct_combinations_with_constructor(array $expected, array $input)
    {
        $country = CountryAlpha2::from($input['country']);
        $phoneNumber = PhoneNumberFactory::createFrom($input['phoneNumber'], $country);
        $instance = new CountryAndPhoneNumber($country, $phoneNumber);
        $this->assertEquals($expected, $instance->toNative());
    }

    public static function correctProvider()
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        $input = [
            'country' => 'NL',
            'phoneNumber' => $phoneUtil->format($phoneUtil->getExampleNumber('NL'), PhoneNumberFormat::E164),
        ];
        $expected = [
            'country' => CountryAlpha2::Netherlands,
            'phoneNumber' => $input['phoneNumber'],
        ];
        yield [$expected, $input];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('incorrectProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_throws_errors_on_incorrect_combinations_with_fromNative(array $expectedErrorMessages, array $input)
    {
        $this->assertValidationError(
            $expectedErrorMessages,
            function () use ($input) {
                CountryAndPhoneNumber::fromNative($input);
            }
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('incorrectProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_throws_errors_on_incorrect_combinations_with_constructor(array $expectedErrorMessages, array $input)
    {
        $this->assertValidationError(
            $expectedErrorMessages,
            function () use ($input) {
                $country = CountryAlpha2::from($input['country']);
                $phoneNumber = (new InternationalPhoneNumber($input['phoneNumber']))->toPhoneNumber();
                new CountryAndPhoneNumber($country, $phoneNumber);
            }
        );
    }

    public static function incorrectProvider()
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        $input = [
            'country' => 'DE',
            'phoneNumber' => $phoneUtil->format($phoneUtil->getExampleNumber('NL'), PhoneNumberFormat::E164),
        ];
        yield [
            ['phoneNumber' => 'Phone number and country are not from the same country. Country is "DE", phone number is "NL"'],
            $input
        ];
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_works_with_schema_generator()
    {
        $this->runOpenapiSchemaTestForCreation(
            CountryAndPhoneNumber::class,
            'CountryAndPhoneNumber-post',
            [
                'type' => 'object',
                'required' => ['country', 'phoneNumber'],
                'properties' => [
                    'country' => new Reference(['$ref' => '#/components/schemas/CountryAlpha2-post']),
                    'phoneNumber' => new Reference(['$ref' => '#/components/schemas/PhoneNumber-post'])
                ],
            ]
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(CountryAndPhoneNumber::class);
    }
}
