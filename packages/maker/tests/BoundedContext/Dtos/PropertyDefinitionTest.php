<?php
namespace Apie\Tests\Maker\BoundedContext\Dtos;

use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\Maker\BoundedContext\Dtos\PropertyDefinition;
use Apie\Maker\ValueObjects\PropertyDefinitionName;
use Apie\Maker\ValueObjects\VendorValueObject;
use PHPUnit\Framework\TestCase;

class PropertyDefinitionTest extends TestCase
{
    use TestWithFaker;

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_works_with_faker()
    {
        $this->runFakerTest(PropertyDefinition::class);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_maps_uniqueness_on_name_property()
    {
        $testItem = new PropertyDefinition(
            new VendorValueObject(VendorValueObject::class),
            PropertyDefinitionName::fromNative('example'),
            true,
            true,
            true
        );
        $this->assertEquals('example', $testItem->__toString());
    }
}
