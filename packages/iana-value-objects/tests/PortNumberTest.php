<?php
namespace Apie\Tests\IanaValueObjects;

use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\IanaValueObjects\PortNumber\ActivePortNumber;
use Apie\IanaValueObjects\PortNumber\PortNumber;
use Apie\IanaValueObjects\PortNumber\TransportType;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class PortNumberTest extends TestCase
{
    use TestWithFaker;
    #[Test]
    public function it_can_be_instantiated_with_a_valid_port()
    {
        $testItem = new PortNumber('80');
        $this->assertEquals('80', $testItem->toNative());
        $info = $testItem->getTransportInfo(TransportType::Sctp);
        $this->assertEquals('HTTP', $info->getDescription());
        $this->assertEquals('http', $info->getServiceName());
        $this->assertTrue($info->isActive());
    }

    #[Test]
    public function it_can_be_instantiated_with_an_inactive_or_reserved_port()
    {
        $testItem = new PortNumber('0');
        $this->assertEquals('0', $testItem->toNative());
    }

    #[Test]
    public function it_throws_an_exception_with_an_invalid_port()
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        new PortNumber('999999');
    }

    #[Test]
    public function it_can_be_instantiated_with_an_active_port()
    {
        $testItem = new ActivePortNumber('443');
        $this->assertEquals('443', $testItem->toNative());
        $info = $testItem->getTransportInfo(TransportType::Sctp);
        
        $this->assertEquals('HTTPS', $info->getDescription());
        $this->assertEquals('https', $info->getServiceName());
        $this->assertTrue($info->isActive());
    }

    
    #[Test]
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(PortNumber::class);
        $this->runFakerTest(ActivePortNumber::class);
    }
}
