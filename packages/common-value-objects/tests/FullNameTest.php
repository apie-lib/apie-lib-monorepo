<?php
namespace Apie\Tests\CommonValueObjects\Identifiers;

use Apie\CommonValueObjects\FullName;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\Fixtures\TestHelpers\TestWithOpenapiSchema;
use cebe\openapi\spec\Reference;
use PHPUnit\Framework\TestCase;

class FullNameTest extends TestCase
{
    use TestWithOpenapiSchema;
    use TestWithFaker;

    /**
     * @test
     */
    public function it_works_with_schema_generator()
    {
        $this->runOpenapiSchemaTestForCreation(
            FullName::class,
            'FullName-post',
            [
                'type' => 'object',
                'properties' => [
                    'gender' => new Reference(['$ref' => '#/components/schemas/Gender-post']),
                    'firstName' => new Reference(['$ref' => '#/components/schemas/FirstName-post']),
                    'lastName' => new Reference(['$ref' => '#/components/schemas/LastName-post']),
                ],
                'required' => ['gender', 'firstName', 'lastName']
            ]
        );
    }

    /**
     * @test
     */
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(FullName::class);
    }
}
