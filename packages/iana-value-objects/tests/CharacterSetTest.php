<?php
namespace Apie\Tests\IanaValueObjects;

use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\IanaValueObjects\ActiveCharacterSet;
use Apie\IanaValueObjects\CharacterSet;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class CharacterSetTest extends TestCase
{
    #[Test]
    public function it_can_be_instantiated_with_a_valid_charset()
    {
        $testItem = new CharacterSet('euc-jp');
        $this->assertEquals('euc-jp', $testItem->toNative());
    }

    #[Test]
    public function it_throws_an_exception_with_an_invalid_charset()
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        new CharacterSet('invalid');
    }

    #[Test]
    public function it_can_be_instantiated_with_an_active_charset()
    {
        $testItem = new ActiveCharacterSet('euc-jp');
        $this->assertEquals('euc-jp', $testItem->toNative());
    }

    #[Test]
    public function it_throws_an_exception_with_an_invalid_active_charset()
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        new ActiveCharacterSet('invalid');
    }
}
