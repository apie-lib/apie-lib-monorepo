<?php
namespace Apie\Tests\IanaValueObjects;

use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\IanaValueObjects\ActiveLanguageExtlang;
use Apie\IanaValueObjects\LanguageExtlang;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class LanguageExtlangTest extends TestCase
{
    #[Test]
    public function it_can_be_instantiated_with_a_valid_tag()
    {
        $testItem = new LanguageExtlang('aao');
        $this->assertEquals('aao', $testItem->toNative());
    }

    #[Test]
    public function it_can_be_instantiated_with_a_deprecated_tag()
    {
        $testItem = new LanguageExtlang('ajp');
        $this->assertEquals('ajp', $testItem->toNative());
    }

    #[Test]
    public function it_throws_an_exception_with_an_invalid_tag()
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        new LanguageExtlang('invalid');
    }

    #[Test]
    public function it_can_be_instantiated_with_an_active_tag()
    {
        $testItem = new ActiveLanguageExtlang('aao');
        $this->assertEquals('aao', $testItem->toNative());
    }

    #[Test]
    public function it_throws_an_exception_with_a_deprecated_tag_when_active_required()
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        new ActiveLanguageExtlang('ajp');
    }
}
