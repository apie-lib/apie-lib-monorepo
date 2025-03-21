<?php
namespace Apie\Tests\RegexTools;

use Apie\RegexTools\CompiledRegularExpression;
use Generator;
use PHPUnit\Framework\TestCase;

class CompiledRegularExpressionTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\DataProvider('provideRegularExpressions')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_define_maximum_and_minimal_string_length_of_a_regex(
        int $expectedMinimal,
        ?int $expectedMaximum,
        string $expectedToString,
        string $regex
    ) {
        $testItem = CompiledRegularExpression::createFromRegexWithoutDelimiters($regex);
        $this->assertEquals($expectedMinimal, $testItem->getMinimalPossibleLength());
        $this->assertEquals($expectedMaximum, $testItem->getMaximumPossibleLength());
        $this->assertEquals($expectedToString, $testItem->__toString());
    }

    public static function provideRegularExpressions(): Generator
    {
        yield 'empty regex' => [0, 0, '', ''];
        yield 'match only empty string' => [0, 0, '^$', '^$'];
        yield 'single character' => [1, 1, 'a', 'a'];
        yield 'escaped character' => [3, 3, '\\$\\\\\\', '\\$\\\\\\'];
        yield 'capture group' => [2, 2, '((a)a)', '((a)a)'];
        yield 'optional' => [0, 1, 'a?', 'a?'];
        yield 'regex with *' => [0, null, 'a*', 'a*'];
        yield 'regex with +' => [1, null, 'a+', 'a+'];
        yield 'repeat static' => [8, 8, 'a{8}', 'a{8}'];
        yield 'repeat static (with spaces)' => [8, 8, 'a{8}', 'a{ 8 }'];
        yield 'repeat range' => [8, 10, 'a{8,10}', 'a{8,10}'];
        yield 'repeat range (with spaces)' => [8, 10, 'a{8,10}', 'a{ 8 , 10 }'];
        yield '[] regex' => [1, 1, '[ab[de]]', '[ab[de]]'];
        yield 'not [] regex' => [1, 1, '[^ab]', '[^ab]'];
        yield 'a or b or c' => [1, 1, 'a|b|c', 'a|b|c'];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('provideConversionRegex')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_modify_a_regex_regex(
        string $expectedCaseInsensitive,
        string $expectedRemoveMarkers,
        string $regex
    ) {
        $testItem = CompiledRegularExpression::createFromRegexWithoutDelimiters($regex);
        $this->assertEquals($expectedRemoveMarkers, $testItem->removeStartAndEndMarkers()->__toString());
        $this->assertEquals($expectedCaseInsensitive, $testItem->toCaseInsensitive()->__toString());
    }

    public static function provideConversionRegex(): Generator
    {
        yield 'empty regex' => ['', '', ''];
        yield 'match only empty string' => ['^$', '', '^$'];
        yield 'single character' => ['(a|A)', 'a', 'a'];
        yield 'escaped character' => ['\\$\\\\\\', '\\$\\\\\\', '\\$\\\\\\'];
        yield 'capture group' => ['(((a|A))(a|A))', '((a)a)', '((a)a)'];
        yield 'optional' => ['(a|A)?', 'a?', 'a?'];
        yield 'regex with *' => ['(a|A)*', 'a*', 'a*'];
        yield 'regex with +' => ['(a|A)+', 'a+', 'a+'];
        yield 'repeat static' => ['(a|A){8}', 'a{8}', 'a{8}'];
        yield 'repeat static (with spaces)' => ['(a|A){8}', 'a{8}', 'a{ 8 }'];
        yield 'repeat range' => ['(a|A){8,10}', 'a{8,10}', 'a{8,10}'];
        yield 'repeat range (with spaces)' => ['(a|A){8,10}', 'a{8,10}', 'a{ 8 , 10 }'];
        yield '[] regex' => ['[(a|A)(b|B)[(d|D)(e|E)]]', '[ab[de]]', '[ab[de]]'];
        yield 'not [] regex' => ['[^(a|A)(b|B)]', '[^ab]', '[^ab]'];
        yield 'a or b or c' => ['(a|A)|(b|B)|(c|C)', 'a|b|c', 'a|b|c'];
    }
}
