<?php
namespace Apie\Tests\CountryAndPhonenumber;

use Apie\CountryAndPhonenumber\PhoneNumber;
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
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(PhoneNumber::class);
    }
}
