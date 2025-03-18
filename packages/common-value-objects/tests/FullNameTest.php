<?php
namespace Apie\Tests\CommonValueObjects;

use Apie\CommonValueObjects\FullName;
use Apie\CommonValueObjects\Gender;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\Fixtures\TestHelpers\TestWithOpenapiSchema;
use Apie\TextValueObjects\FirstName;
use Apie\TextValueObjects\LastName;
use cebe\openapi\spec\Reference;
use PHPUnit\Framework\TestCase;

class FullNameTest extends TestCase
{
    use TestWithOpenapiSchema;
    use TestWithFaker;

    #[\PHPUnit\Framework\Attributes\Test]
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

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_give_me_a_word_index()
    {
        $testItem = new FullName(
            Gender::FEMALE,
            new FirstName('Peppa Porky'),
            new LastName('Pig')
        );
        $this->assertEquals(['mrs' => 1, 'peppa' => 1, 'porky' => 1, 'pig' => 1], $testItem->getIndexes());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_be_displayed_as_string()
    {
        $testItem = new FullName(
            Gender::MALE,
            new FirstName('Pieter'),
            new LastName('Jordaan')
        );
        $this->assertEquals('Mr. Pieter Jordaan', $testItem->__toString());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(FullName::class);
    }
}
