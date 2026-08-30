<?php
namespace Apie\Tests\CountryAndPhoneNumber;

use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\CountryAndPhoneNumber\InternationalPhoneNumber;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\Fixtures\TestHelpers\TestWithOpenapiSchema;
use libphonenumber\NumberParseException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class InternationalPhoneNumberTest extends TestCase
{
    use TestWithFaker;
    use TestWithOpenapiSchema;

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_works_with_schema_generator()
    {
        $this->runOpenapiSchemaTestForCreation(
            InternationalPhoneNumber::class,
            'InternationalPhoneNumber-post',
            [
                'type' => 'string',
                'format' => 'internationalphonenumber',
                'description' => true,
                'example' => '+12025550123',
            ]
        );
    }

    #[Test]
    public function it_does_not_allow_invalid_phone_numbers()
    {
        $this->expectException(NumberParseException::class);
        InternationalPhoneNumber::fromNative('+316112233445566778899');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(InternationalPhoneNumber::class);
    }
}
