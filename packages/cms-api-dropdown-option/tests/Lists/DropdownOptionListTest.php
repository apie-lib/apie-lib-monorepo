<?php
namespace Apie\Tests\CmsApiDropdownOption\Lists;

use Apie\CmsApiDropdownOption\Dtos\DropdownOption;
use Apie\CmsApiDropdownOption\Lists\DropdownOptionList;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\Fixtures\TestHelpers\TestWithOpenapiSchema;
use cebe\openapi\spec\Reference;
use PHPUnit\Framework\TestCase;

class DropdownOptionListTest extends TestCase
{
    use TestWithFaker;
    use TestWithOpenapiSchema;

    /**
     * @test
     */
    public function it_works_with_schema_generator()
    {
        $this->runOpenapiSchemaTestForCreation(
            DropdownOptionList::class,
            'DropdownOptionList-post',
            [
                'type' => 'array',
                'items' => new Reference(['$ref' => '#/components/schemas/DropdownOption-post']),
            ]
        );
    }

    /**
     * @test
     */
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(DropdownOptionList::class);
    }

    /**
     * @test
     */
    public function it_works_as_an_array()
    {
        $option = new DropdownOption('value', 'display');
        $testItem = new DropdownOptionList([$option]);
        $this->assertSame($option, $testItem[0]);
    }
}