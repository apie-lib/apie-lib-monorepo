<?php
namespace Apie\Tests\IanaValueObjects;

use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\IanaValueObjects\LanguageTag\ActiveLanguageRegion;
use Apie\IanaValueObjects\LanguageTag\LanguageRegion;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class LanguageRegionTest extends TestCase
{
    use TestWithFaker;

    #[Test]
    public function it_can_be_instantiated_with_a_valid_tag()
    {
        $testItem = new LanguageRegion('bE');
        $this->assertEquals('BE', $testItem->toNative());
        $this->assertEquals($testItem, $testItem->toPreferredValue());
        $this->assertTrue($testItem->isActive());
        $this->assertEquals('BE', $testItem->getSubtag());
        $this->assertEquals('Belgium', $testItem->getDescription()->toNative());
        $this->assertEquals(new \DateTimeImmutable('2005-10-16'), $testItem->getAdded());
        $this->assertNull($testItem->getDeprecated());
        $this->assertEquals(null, $testItem->getPrefix());
        $this->assertEquals(null, $testItem->getMacrolanguage());
    }

    #[Test]
    public function it_can_be_instantiated_with_a_deprecated_tag()
    {
        $testItem = new LanguageRegion('BU');
        $this->assertEquals('BU', $testItem->toNative());
    }

    #[Test]
    public function it_can_return_a_preferred_value()
    {
        $testItem = new LanguageRegion('bu');
        $preferred = $testItem->toPreferredValue();
        $this->assertEquals('MM', $preferred->toNative());
    }

    #[Test]
    public function it_returns_itself_if_no_preferred_value()
    {
        $testItem = new LanguageRegion('be');
        $preferred = $testItem->toPreferredValue();
        $this->assertEquals('BE', $preferred->toNative());
    }

    #[Test]
    public function it_throws_an_exception_with_an_invalid_tag()
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        new LanguageRegion('invalid');
    }

    #[Test]
    public function it_can_be_instantiated_with_an_active_tag()
    {
        $testItem = new ActiveLanguageRegion('bs');
        $this->assertEquals('BS', $testItem->toNative());
    }

    #[Test]
    public function it_throws_an_exception_with_a_deprecated_tag_when_active_required()
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        new ActiveLanguageRegion('bu');
    }

    #[Test]
    public function it_can_provide_all_options()
    {
        $this->assertNotEquals(
            LanguageRegion::getOptions()->toArray(),
            ActiveLanguageRegion::getOptions()->toArray()
        );
    }

    #[Test]
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(LanguageRegion::class, interval: 100);
        $this->runFakerTest(ActiveLanguageRegion::class, interval: 100);
    }
}
