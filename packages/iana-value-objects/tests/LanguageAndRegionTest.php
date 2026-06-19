<?php
namespace Apie\Tests\IanaValueObjects;

use Apie\IanaValueObjects\Language;
use Apie\IanaValueObjects\LanguageAndRegion;
use Apie\IanaValueObjects\LanguageRegion;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class LanguageAndRegionTest extends LanguageRegionTest
{
    #[Test]
    public function it_can_be_instantiated_with_a_valid_tag()
    {
        $testItem = new LanguageAndRegion(new Language('en'), new LanguageRegion('US'));
        $this->assertEquals('en-US', $testItem->toNative());
    }

    #[Test]
    public function it_can_be_instantiated_with_no_region()
    {
        $testItem = new LanguageAndRegion(new Language('en'));
        $this->assertEquals('en', $testItem->toNative());
    }

    #[Test]
    public function it_can_be_instantiated_from_a_string()
    {
        $testItem = LanguageAndRegion::fromNative('en-US');
        $expected = new LanguageAndRegion(new Language('en'), new LanguageRegion('US'));
        $this->assertEquals($expected, $testItem);
    }

    #[Test]
    public function it_can_be_instantiated_from_a_string_without_region()
    {
        $testItem = LanguageAndRegion::fromNative('en');
        $expected = new LanguageAndRegion(new Language('en'));
        $this->assertEquals($expected, $testItem);
    }

    #[Test]
    #[DataProvider('provideInvalidTags')]
    public function it_can_be_converted_to_a_preferred_value(string $expected, string $input)
    {
        $testItem = LanguageAndRegion::fromNative($input);
        $preferredItem = $testItem->toPreferredValue();
        $this->assertEquals($expected, $preferredItem->toNative());
    }

    public static function provideInvalidTags(): array
    {
        return [
            ['en-US', 'en-us'],
            ['nbr-MM', 'nns-BU'],
        ];
    }
}
