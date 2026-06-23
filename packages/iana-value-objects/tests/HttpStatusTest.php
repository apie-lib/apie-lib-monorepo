<?php
namespace Apie\Tests\IanaValueObjects;

use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\IanaValueObjects\ActiveHttpStatus;
use Apie\IanaValueObjects\HttpStatus;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class HttpStatusTest extends TestCase
{
    #[Test]
    public function it_can_be_instantiated_with_a_valid_status_code()
    {
        $testItem = new HttpStatus('200');
        $this->assertEquals('200', $testItem->toNative());
    }

    #[Test]
    public function it_can_be_instantiated_with_a_deprecated_or_obsolete_status_code()
    {
        // 510 is Obsoleted
        $testItem = new HttpStatus('510');
        $this->assertEquals('510', $testItem->toNative());
    }

    #[Test]
    public function it_throws_an_exception_with_an_invalid_status_code()
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        new HttpStatus('999');
    }

    #[Test]
    public function it_can_be_instantiated_with_an_active_status_code()
    {
        $testItem = new ActiveHttpStatus('200');
        $this->assertEquals('200', $testItem->toNative());
    }
}
