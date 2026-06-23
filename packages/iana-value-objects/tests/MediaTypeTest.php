<?php
namespace Apie\Tests\IanaValueObjects;

use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\IanaValueObjects\ActiveMediaType;
use Apie\IanaValueObjects\MediaType;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class MediaTypeTest extends TestCase
{
    #[Test]
    public function it_can_be_instantiated_with_a_valid_media_type()
    {
        $testItem = new MediaType('application/json');
        $this->assertEquals('application/json', $testItem->toNative());
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
}
