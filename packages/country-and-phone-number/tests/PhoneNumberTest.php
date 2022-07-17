<?php
namespace Apie\Tests\CountryAndPhoneNumber;

use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\CountryAndPhoneNumber\GermanPhoneNumber;
use Apie\CountryAndPhoneNumber\PhoneNumber;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\Fixtures\TestHelpers\TestWithOpenapiSchema;
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
     * @test
     */
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(PhoneNumber::class);
    }
}
