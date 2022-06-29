<?php
namespace Apie\Tests\DoctrineEntityConverter\Embeddables;

use Apie\DoctrineEntityConverter\Embeddables\MixedType;
use Apie\Fixtures\ValueObjects\CompositeValueObjectExample;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

class MixedTypeTest extends TestCase
{
    private mixed $testProperty;

    /**
     * @test
     * @dataProvider mixedProvider
     */
    public function it_can_store_and_restore_anything(mixed $input)
    {
        $object = MixedType::createFrom($input);
        $refl = new ReflectionProperty(__CLASS__, 'testProperty');
        $object->inject($this, $refl);
        $this->assertEquals($this->testProperty, $input);
    }

    public function mixedProvider(): iterable
    {
        yield [null];
        yield ['string'];
        yield [42];
        yield [1.5];
        yield [new CompositeValueObjectExample()];
    }
}
