<?php
namespace Apie\Tests\IanaValueObjects;

use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\IanaValueObjects\LanguageTag\ActiveLanguageScript;
use Apie\IanaValueObjects\LanguageTag\LanguageScript;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class LanguageScriptTest extends TestCase
{
    use TestWithFaker;

    #[Test]
    public function it_can_be_instantiated_with_a_valid_subtag()
    {
        $testItem = new LanguageScript('adlm');
        $this->assertEquals('Adlm', $testItem->toNative());
        $this->assertEquals($testItem, $testItem->toPreferredValue());
        $this->assertTrue($testItem->isActive());
        $this->assertEquals('Adlm', $testItem->getSubtag());
        $this->assertEquals('Adlam', $testItem->getDescription()->toNative());
        $this->assertEquals(new \DateTimeImmutable('2014-12-11'), $testItem->getAdded());
        $this->assertNull($testItem->getDeprecated());
        $this->assertEquals(null, $testItem->getPrefix());
        $this->assertEquals(null, $testItem->getMacrolanguage());
    }

    #[Test]
    public function it_throws_an_exception_with_an_invalid_subtag()
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        new LanguageScript('invalid');
    }

    #[Test]
    public function it_can_be_instantiated_with_an_active_subtag()
    {
        $testItem = new ActiveLanguageScript('adlm');
        $this->assertEquals('Adlm', $testItem->toNative());
    }

    #[Test]
    public function it_throws_an_exception_with_an_invalid_active_subtag()
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        new ActiveLanguageScript('invalid');
    }

    #[Test]
    public function it_can_provide_all_options()
    {
        $this->assertEquals(
            LanguageScript::getOptions()->toArray(),
            ActiveLanguageScript::getOptions()->toArray()
        );
    }

    #[Test]
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(LanguageScript::class, interval: 100);
        $this->runFakerTest(ActiveLanguageScript::class, interval: 100);
    }
}
