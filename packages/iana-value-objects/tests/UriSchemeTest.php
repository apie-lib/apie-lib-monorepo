<?php
namespace Apie\Tests\IanaValueObjects;

use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\IanaValueObjects\ActiveUriScheme;
use Apie\IanaValueObjects\UriScheme;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class UriSchemeTest extends TestCase
{
    #[Test]
    public function it_can_be_instantiated_with_a_valid_scheme()
    {
        $testItem = new UriScheme('http');
        $this->assertEquals('http', $testItem->toNative());
    }

    #[Test]
    public function it_can_be_instantiated_with_a_historical_scheme()
    {
        $testItem = new UriScheme('bb');
        $this->assertEquals('bb', $testItem->toNative());
    }

    #[Test]
    public function it_throws_an_exception_with_an_invalid_scheme()
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        new UriScheme('invalid');
    }

    #[Test]
    public function it_can_be_instantiated_with_an_active_scheme()
    {
        $testItem = new ActiveUriScheme('http');
        $this->assertEquals('http', $testItem->toNative());
    }

    #[Test]
    public function it_throws_an_exception_with_a_historical_scheme_when_active_required()
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        new ActiveUriScheme('bb');
    }
}
