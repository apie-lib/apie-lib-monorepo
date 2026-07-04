<?php
namespace Apie\Tests\IanaValueObjects;

use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\IanaValueObjects\MediaType\ActiveMediaType;
use Apie\IanaValueObjects\MediaType\MediaType;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class MediaTypeTest extends TestCase
{
    use TestWithFaker;

    #[Test]
    public function it_can_be_instantiated_with_a_valid_media_type()
    {
        $testItem = new MediaType('application/json');
        $this->assertEquals('application/json', $testItem->toNative());
        $this->assertEquals('json', $testItem->getName());
        $this->assertEquals('application/json', $testItem->getTemplate());
        $this->assertEquals('[RFC8259]', $testItem->getReference());
        $this->assertTrue($testItem->isActive());
    }

    #[Test]
    public function it_throws_an_exception_with_an_invalid_media_type()
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        new MediaType('invalid');
    }

    #[Test]
    public function it_can_be_instantiated_with_an_active_media_type()
    {
        $testItem = new ActiveMediaType('text/html');
        $this->assertEquals('text/html', $testItem->toNative());
    }

    #[Test]
    public function it_throws_an_exception_with_an_invalid_active_media_type()
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        new ActiveMediaType('invalid');
    }

    #[Test]
    public function it_can_provide_all_options()
    {
        $this->assertEquals(
            MediaType::getOptions()->toArray(),
            ActiveMediaType::getOptions()->toArray()
        );
    }

    
    #[Test]
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(MediaType::class, interval: 100);
        $this->runFakerTest(ActiveMediaType::class);
    }
}
