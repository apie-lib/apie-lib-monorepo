<?php
namespace Apie\Tests\Maker\BoundedContext\Resources;

use Apie\Core\Identifiers\Identifier;
use Apie\Core\Identifiers\PascalCaseSlug;
use Apie\Core\Identifiers\Ulid;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\Maker\BoundedContext\Dtos\PropertyDefinition;
use Apie\Maker\BoundedContext\Identifiers\BoundedContextDefinitionIdentifier;
use Apie\Maker\BoundedContext\Identifiers\ResourceDefinitionIdentifier;
use Apie\Maker\BoundedContext\Lists\PropertyDefinitionList;
use Apie\Maker\BoundedContext\Resources\ResourceDefinition;
use Apie\Maker\Enums\IdType;
use Apie\Maker\ValueObjects\PropertyDefinitionName;
use Apie\Maker\ValueObjects\VendorValueObject;
use PHPUnit\Framework\TestCase;

class ResourceDefinitionTest extends TestCase
{
    use TestWithFaker;

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_works_as_intended()
    {
        $propertyDefinition = new PropertyDefinition(
            new VendorValueObject(VendorValueObject::class),
            new PropertyDefinitionName('propertyName'),
            true,
            true,
            true
        );
        $id = ResourceDefinitionIdentifier::createRandom();
        $testItem = new ResourceDefinition(
            $id,
            IdType::Email,
            PascalCaseSlug::fromNative('InputClassName'),
            BoundedContextDefinitionIdentifier::fromNameAndUlid(
                Identifier::fromNative('subdomain'),
                Ulid::createRandom()
            ),
            new PropertyDefinitionList([
                $propertyDefinition,
            ])
        );
        $this->assertSame($id, $testItem->getId());
        $this->assertEquals('InputClassName', $testItem->getName()->toNative());

        $this->assertEquals(1, $testItem->getProperties()->count(), 'I expect the id property being filtered out');
        $this->assertEquals($propertyDefinition, $testItem->getProperties()[$propertyDefinition]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_works_with_faker()
    {
        $this->runFakerTest(ResourceDefinition::class);
    }
}
