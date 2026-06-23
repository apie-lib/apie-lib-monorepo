<?php
namespace Apie\Tests\IanaValueObjects;

use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\IanaValueObjects\ActiveTopLevelDomain;
use Apie\IanaValueObjects\TopLevelDomain;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class TopLevelDomainTest extends TestCase
{
    #[Test]
    public function it_can_be_instantiated_with_a_valid_tld()
    {
        $testItem = new TopLevelDomain('cOm');
        $this->assertEquals('COM', $testItem->toNative());
    }

    #[Test]
    public function it_throws_an_exception_with_an_invalid_tld()
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        new TopLevelDomain('invalid');
    }

    #[Test]
    public function it_can_be_instantiated_with_an_active_tld()
    {
        $testItem = new ActiveTopLevelDomain('cOm');
        $this->assertEquals('COM', $testItem->toNative());
    }

    #[Test]
    public function it_throws_an_exception_with_an_invalid_active_tld()
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        new ActiveTopLevelDomain('invalid');
    }
}
