<?php
namespace Apie\Tests\Maker\BoundedContext\Resources;

use Apie\Core\Identifiers\Identifier;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\Maker\BoundedContext\Resources\BoundedContextDefinition;
use PHPUnit\Framework\TestCase;

class BoundedContextDefinitionTest extends TestCase
{
    use TestWithFaker;
    /**
     * @test
     */
    public function it_works_as_intended()
    {
        $testItem = new BoundedContextDefinition(new Identifier('test'));
        $id = $testItem->getId();
        $this->assertStringStartsWith('test', $id->toNative());
    }

    /**
     * @test
     */
    public function it_works_with_faker()
    {
        $this->runFakerTest(BoundedContextDefinition::class);
    }
}