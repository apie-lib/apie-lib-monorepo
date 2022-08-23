<?php
namespace Apie\Tests\CountryAndPhoneNumber;

use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\CountryAndPhoneNumber\BelgianPhoneNumber;
use Apie\CountryAndPhoneNumber\BritishPhoneNumber;
use Apie\CountryAndPhoneNumber\CanadianPhoneNumber;
use Apie\CountryAndPhoneNumber\ChinesePhoneNumber;
use Apie\CountryAndPhoneNumber\DutchPhoneNumber;
use Apie\CountryAndPhoneNumber\FrenchPhoneNumber;
use Apie\CountryAndPhoneNumber\GermanPhoneNumber;
use Apie\CountryAndPhoneNumber\JapanesePhoneNumber;
use Apie\CountryAndPhoneNumber\MexicanPhoneNumber;
use Apie\CountryAndPhoneNumber\PhoneNumber;
use Apie\CountryAndPhoneNumber\USPhoneNumber;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\Fixtures\TestHelpers\TestWithOpenapiSchema;
use Generator;
use PHPUnit\Framework\TestCase;

class PhoneNumberTest extends TestCase
{
    use TestWithFaker;
    use TestWithOpenapiSchema;

    /**
     * @test
     */
    public function it_works_with_schema_generator()
    {
        $this->runOpenapiSchemaTestForCreation(
            PhoneNumber::class,
            'PhoneNumber-post',
            [
                'type' => 'string',
                'format' => 'phonenumber',
            ]
        );
    }

    /**
     * @test
     */
    public function it_throws_error_on_incorrect_country()
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        new GermanPhoneNumber('+3161234567');
    }

    /**
     * @param class-string<PhoneNumber> $className
     * @dataProvider phoneNumberClassProvider
     * @test
     */
    public function it_works_with_apie_faker(string $className)
    {
        $this->runFakerTest($className);
    }

    public function phoneNumberClassProvider(): Generator
    {
        yield [PhoneNumber::class];
        yield [BelgianPhoneNumber::class];
        yield [BritishPhoneNumber::class];
        yield [CanadianPhoneNumber::class];
        yield [ChinesePhoneNumber::class];
        yield [DutchPhoneNumber::class];
        yield [FrenchPhoneNumber::class];
        yield [GermanPhoneNumber::class];
        yield [JapanesePhoneNumber::class];
        yield [MexicanPhoneNumber::class];
        yield [USPhoneNumber::class];
    }
}
