<?php
namespace Apie\Tests\IanaValueObjects;

use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\IanaValueObjects\LanguageTag\ActiveLanguage;
use Apie\IanaValueObjects\LanguageTag\Language;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class LanguageTest extends TestCase
{
    #[Test]
    public function it_can_be_instantiated_with_a_valid_subtag()
    {
        $testItem = new Language('en');
        $this->assertEquals('en', $testItem->toNative());
    }

    #[Test]
    public function it_throws_an_exception_with_an_invalid_subtag()
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        new Language('invalid');
    }

    #[Test]
    public function it_can_be_instantiated_with_an_active_subtag()
    {
        $testItem = new ActiveLanguage('en');
        $this->assertEquals('en', $testItem->toNative());
    }

    #[Test]
    public function it_throws_an_exception_with_an_invalid_active_subtag()
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        new ActiveLanguage('invalid');
    }
}
