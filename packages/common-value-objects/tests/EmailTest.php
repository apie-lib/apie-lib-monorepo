<?php
namespace Apie\Tests\CommonValueObjects;

use Apie\CommonValueObjects\Email;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\Fixtures\TestHelpers\TestWithOpenapiSchema;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    use TestWithOpenapiSchema;
    use TestWithFaker;
    
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_works_with_schema_generator()
    {
        $this->runOpenapiSchemaTestForCreation(
            Email::class,
            'Email-post',
            [
                'type' => 'string',
                'format' =>  'email',
            ]
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(Email::class);
    }
}
