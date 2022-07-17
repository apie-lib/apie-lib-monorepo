<?php
namespace Apie\Tests\CountryAndPhoneNumber;

use Apie\CountryAndPhoneNumber\CountryAndPhoneNumber;
use Apie\CountryAndPhoneNumber\Exceptions\PhoneNumberAndCountryMismatch;
use Apie\CountryAndPhoneNumber\Factories\PhoneNumberFactory;
use Apie\CountryAndPhoneNumber\InternationalPhoneNumber;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\Fixtures\TestHelpers\TestWithOpenapiSchema;
use cebe\openapi\spec\Reference;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use PHPUnit\Framework\TestCase;
use PrinsFrank\Standards\Country\ISO3166_1_Alpha_2;

class CountryAndPhoneNumberTest extends TestCase
{
    use TestWithFaker;
    use TestWithOpenapiSchema;

    /**
     * @test
     * @dataProvider correctProvider
     */
    public function it_can_instantiate_correct_combinations_with_fromNative(array $expected, array $input)
    {
        $this->assertEquals($expected, CountryAndPhoneNumber::fromNative($input)->toNative());
    }

    /**
     * @test
     * @dataProvider correctProvider
     */
    public function it_can_instantiate_correct_combinations_with_constructor(array $expected, array $input)
    {
        $country = ISO3166_1_Alpha_2::from($input['country']);
        $phoneNumber = PhoneNumberFactory::createFrom($input['phoneNumber'], $country);
        $instance = new CountryAndPhoneNumber($country, $phoneNumber);
        $this->assertEquals($expected, $instance->toNative());
    }

    public function correctProvider()
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        $input = [
            'country' => 'NL',
            'phoneNumber' => $phoneUtil->format($phoneUtil->getExampleNumber('NL'), PhoneNumberFormat::E164),
        ];
        $expected = [
            'country' => ISO3166_1_Alpha_2::Netherlands_the,
            'phoneNumber' => $input['phoneNumber'],
        ];
        yield [$expected, $input];
    }

    /**
     * @test
     * @dataProvider incorrectProvider
     */
    public function it_throws_errors_on_incorrect_combinations_with_fromNative(string $expectedClass, array $input)
    {
        $this->expectException($expectedClass);
        CountryAndPhoneNumber::fromNative($input);
    }

    /**
     * @test
     * @dataProvider incorrectProvider
     */
    public function  it_throws_errors_on_incorrect_combinations_with_constructor(string $expectedClass, array $input)
    {
        $country = ISO3166_1_Alpha_2::from($input['country']);
        $phoneNumber = (new InternationalPhoneNumber($input['phoneNumber']))->toPhoneNumber();
        $this->expectException($expectedClass);
        new CountryAndPhoneNumber($country, $phoneNumber);
    }

    public function incorrectProvider()
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        $input = [
            'country' => 'DE',
            'phoneNumber' => $phoneUtil->format($phoneUtil->getExampleNumber('NL'), PhoneNumberFormat::E164),
        ];
        yield [PhoneNumberAndCountryMismatch::class, $input];
    }

    /**
     * @test
     */
    public function it_works_with_schema_generator()
    {
        $this->runOpenapiSchemaTestForCreation(
            CountryAndPhoneNumber::class,
            'CountryAndPhoneNumber-post',
            [
                'type' => 'object',
                'required' => ['country', 'phoneNumber'],
                'properties' => [
                    'country' => new Reference(['$ref' => 'ISO3166_1_Alpha_2-post']),
                    'phoneNumber' => new Reference(['$ref' => 'PhoneNumber-post'])
                ],
            ]
        );
    }

    /**
     * @test
     */
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(CountryAndPhoneNumber::class);
    }
}
