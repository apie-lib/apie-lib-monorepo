<?php
namespace Apie\Tests\CountryAndPhoneNumber;

use Apie\CountryAndPhonenumber\InternationalPhoneNumber;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\Fixtures\TestHelpers\TestWithOpenapiSchema;
use PHPUnit\Framework\TestCase;

class InternationalPhoneNumberTest extends TestCase
{
    use TestWithFaker;
    use TestWithOpenapiSchema;

    /**
     * @test
     */
    public function it_works_with_schema_generator()
    {
        $this->runOpenapiSchemaTestForCreation(
            InternationalPhoneNumber::class,
            'InternationalPhoneNumber-post',
            [
                'type' => 'string',
                'format' => 'internationalphonenumber',
            ]
        );
    }

    /**
     * @test
     */
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(InternationalPhoneNumber::class);
    }
}
