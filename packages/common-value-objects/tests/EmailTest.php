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
                'description' => true,
            ]
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_split_an_email_into_its_local_part_and_hostname()
    {
        $email = new Email('Mail+Alias@example.com');

        $this->assertEquals('Mail+Alias', $email->getLocalPart()->toNative());
        $this->assertEquals('example.com', $email->getHostname()->toNative());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(Email::class);
    }
}
