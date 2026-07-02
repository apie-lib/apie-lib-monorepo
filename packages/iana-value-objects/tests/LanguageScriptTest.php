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
        $this->runFakerTest(LanguageScript::class);
    }
}
