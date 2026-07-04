<?php
namespace Apie\Tests\IanaValueObjects;

use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\IanaValueObjects\UriScheme\ActiveUriScheme;
use Apie\IanaValueObjects\UriScheme\UriScheme;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class UriSchemeTest extends TestCase
{
    use TestWithFaker;
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

    #[Test]
    public function it_can_provide_all_options()
    {
        $this->assertNotEquals(
            UriScheme::getOptions()->toArray(),
            ActiveUriScheme::getOptions()->toArray()
        );
    }
    
    #[Test]
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(UriScheme::class);
        $this->runFakerTest(ActiveUriScheme::class, interval: 100);
    }
}
