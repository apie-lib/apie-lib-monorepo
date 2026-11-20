<?php
namespace Apie\Tests\IanaValueObjects;

use Apie\Core\Lists\StringSet;
use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\IanaValueObjects\CharacterSet\ActiveCharacterSet;
use Apie\IanaValueObjects\CharacterSet\CharacterSet;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class CharacterSetTest extends TestCase
{
    use TestWithFaker;

    #[Test]
    public function it_can_be_instantiated_with_a_valid_charset()
    {
        $testItem = new CharacterSet('euc-jp');
        $this->assertEquals('euc-jp', $testItem->toNative());
        $this->assertEquals('EUC-JP', $testItem->getPreferredMimeName());
        $this->assertEquals('Extended_UNIX_Code_Packed_Format_for_Japanese', $testItem->getName());
        $this->assertEquals(18, $testItem->getMibEnum());
        $source = 'Standardized by OSF, UNIX International, and UNIX Systems
Laboratories Pacific.  Uses ISO 2022 rules to select
code set 0: US-ASCII (a single 7-bit byte set)
code set 1: JIS X0208-1990 (a double 8-bit byte set)
restricted to A0-FF in both bytes
code set 2: Half Width Katakana (a single 7-bit byte set)
requiring SS2 as the character prefix
code set 3: JIS X0212-1990 (a double 7-bit byte set)
restricted to A0-FF in both bytes
requiring SS3 as the character prefix';
        $this->assertEquals($source, $testItem->getSource());
        $this->assertNull($testItem->getReference());
        $this->assertEquals(
            new StringSet(['csEUCPkdFmtJapanese', 'EUC-JP']),
            $testItem->getAliases()
        );
        $this->assertNull($testItem->getNote());
        $this->assertTrue($testItem->isActive());
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

    #[Test]
    public function it_can_provide_all_options()
    {
        $this->assertEquals(
            CharacterSet::getOptions()->toArray(),
            ActiveCharacterSet::getOptions()->toArray()
        );
    }

    #[Test]
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(CharacterSet::class);
        $this->runFakerTest(ActiveCharacterSet::class);
    }
}
