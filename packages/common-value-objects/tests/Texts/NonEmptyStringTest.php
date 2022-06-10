<?php
namespace Apie\Tests\CommonValueObjects\Texts;

use Apie\CommonValueObjects\Texts\NonEmptyString;
use PHPUnit\Framework\TestCase;

class NonEmptyStringTest extends TestCase
{
    /**
     * @test
     * @dataProvider inputProvider
     */
    public function it_allows_all_strings_that_are_not_empty(string $expected, string $input)
    {
        $testItem = new NonEmptyString($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    public function inputProvider()
    {
        yield ['test', 'test'];
        yield ['trimmed', '   trimmed   '];
    }
}
