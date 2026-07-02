<?php
namespace Apie\Tests\IanaValueObjects;

use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\IanaValueObjects\LanguageTag\ActiveLanguageVariant;
use Apie\IanaValueObjects\LanguageTag\LanguageVariant;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class LanguageVariantTest extends TestCase
{
    use TestWithFaker;

    #[Test]
    public function it_can_be_instantiated_with_a_valid_tag()
    {
        $testItem = new LanguageVariant('1606nict');
        $this->assertEquals('1606nict', $testItem->toNative());
    }

    #[Test]
    public function it_can_be_instantiated_with_a_deprecated_tag()
    {
        $testItem = new LanguageVariant('heploc');
        $this->assertEquals('heploc', $testItem->toNative());
    }

    #[Test]
    public function it_can_return_a_preferred_value()
    {
        $testItem = new LanguageVariant('heploc');
        $preferred = $testItem->toPreferredValue();
        $this->assertEquals('alalc97', $preferred->toNative());
    }

    #[Test]
    public function it_returns_itself_if_no_preferred_value()
    {
        $testItem = new LanguageVariant('1606nict');
        $preferred = $testItem->toPreferredValue();
        $this->assertEquals('1606nict', $preferred->toNative());
    }

    #[Test]
    public function it_throws_an_exception_with_an_invalid_tag()
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        new LanguageVariant('invalid');
    }

    #[Test]
    public function it_can_be_instantiated_with_an_active_tag()
    {
        $testItem = new ActiveLanguageVariant('1606nict');
        $this->assertEquals('1606nict', $testItem->toNative());
    }

    #[Test]
    public function it_throws_an_exception_with_a_deprecated_tag_when_active_required()
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        new ActiveLanguageVariant('heploc');
    }

    #[Test]
    public function it_can_provide_all_options()
    {
        $this->assertNotEquals(
            LanguageVariant::getOptions()->toArray(),
            ActiveLanguageVariant::getOptions()->toArray()
        );
    }
    
    #[Test]
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(LanguageVariant::class);
        $this->runFakerTest(ActiveLanguageVariant::class);
    }
}
