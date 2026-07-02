<?php
namespace Apie\Tests\IanaValueObjects;

use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\IanaValueObjects\HttpHeader\ActiveHttpHeader;
use Apie\IanaValueObjects\HttpHeader\HttpHeader;
use Apie\IanaValueObjects\HttpHeader\HttpHeaderStatus;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class HttpHeaderTest extends TestCase
{
    use TestWithFaker;
    
    #[Test]
    public function it_can_be_instantiated_with_a_valid_header()
    {
        $testItem = new HttpHeader('content-type');
        $this->assertEquals('Content-Type', $testItem->toNative());
        $this->assertEquals(HttpHeaderStatus::Permanent, $testItem->getStatus());
        $this->assertEquals(null, $testItem->getStructuredType());
        $this->assertEquals(null, $testItem->getComments());
        $this->assertTrue($testItem->isActive());
    }

    #[Test]
    public function it_can_be_instantiated_with_a_deprecated_or_obsolete_header()
    {
        $testItem = new HttpHeader('accept-charset');
        $this->assertEquals('Accept-Charset', $testItem->toNative());
    }

    #[Test]
    public function it_throws_an_exception_with_an_invalid_header()
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        new HttpHeader('invalid');
    }

    #[Test]
    public function it_can_be_instantiated_with_an_active_header()
    {
        $testItem = new ActiveHttpHeader('content-type');
        $this->assertEquals('Content-Type', $testItem->toNative());
    }

    #[Test]
    public function it_throws_an_exception_with_a_deprecated_or_obsolete_header_when_active_required()
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        new ActiveHttpHeader('accept-charset');
    }

    #[Test]
    public function it_can_provide_all_options()
    {
        $this->assertNotEquals(
            HttpHeader::getOptions()->toArray(),
            ActiveHttpHeader::getOptions()->toArray()
        );
    }

    #[Test]
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(HttpHeader::class);
        $this->runFakerTest(ActiveHttpHeader::class);
    }
}
