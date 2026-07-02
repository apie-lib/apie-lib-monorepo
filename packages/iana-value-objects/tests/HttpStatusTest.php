<?php
namespace Apie\Tests\IanaValueObjects;

use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\IanaValueObjects\HttpStatus\ActiveHttpStatus;
use Apie\IanaValueObjects\HttpStatus\HttpStatus;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class HttpStatusTest extends TestCase
{
    use TestWithFaker;
    #[Test]
    public function it_can_be_instantiated_with_a_valid_status_code()
    {
        $testItem = new HttpStatus('200');
        $this->assertEquals('200', $testItem->toNative());
        $this->assertEquals('200', $testItem->getValue());
        $this->assertEquals('OK', $testItem->getDescription());
        $this->assertEquals('[RFC9110, Section 15.3.1]', $testItem->getReference());
        $this->assertTrue($testItem->isActive());
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

    #[Test]
    public function it_can_provide_all_options()
    {
        $this->assertNotEquals(
            HttpStatus::getOptions()->toArray(),
            ActiveHttpStatus::getOptions()->toArray()
        );
    }

    #[Test]
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(HttpStatus::class);
        $this->runFakerTest(ActiveHttpStatus::class);
    }
}
