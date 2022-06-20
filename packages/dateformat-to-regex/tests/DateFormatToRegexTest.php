<?php
namespace Apie\Tests\DateformatToRegex;

use Apie\DateformatToRegex\DateFormatToRegex;
use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class DateFormatToRegexTest extends TestCase
{
    /**
     * @dataProvider formatProvider
     */
    public function testFormatToRegex(string $format)
    {
        $regex = DateFormatToRegex::formatToRegex($format);
        for ($i = 0; $i < 1000; $i++) {
            $timestamp = random_int(-1000, 200000000);
            $date = DateTimeImmutable::createFromFormat('U', $timestamp);
            $dateString = $date->format($format);
            $this->assertMatchesRegularExpression($regex, $dateString, $dateString . ' matches ' . $regex);
        }
    }

    public function formatProvider()
    {
        yield [DateTime::ATOM];
        yield [DateTime::COOKIE];
        yield [DateTime::ISO8601];
        yield [DateTime::RFC1036];
        yield [DateTime::RFC3339_EXTENDED];
        yield ['d'];
        yield ['j'];
        yield ['D'];
        yield ['l'];
        yield ['N'];
        yield ['w'];
        yield ['S'];
        yield ['z'];
        yield ['W'];
        yield ['F'];
        yield ['M'];
        yield ['m'];
        yield ['n'];
        yield ['t'];
        yield ['L'];
        yield ['o'];
        yield ['Y'];
        yield ['y'];
        yield ['a'];
        yield ['A'];
        yield ['B'];
        yield ['g'];
        yield ['h'];
        yield ['G'];
        yield ['H'];
        yield ['i'];
        yield ['s'];
        yield ['u'];
        yield ['v'];
        yield ['I'];
        yield ['O'];
        yield ['P'];
        yield ['p'];
        yield ['Z'];
        yield ['U'];
        yield ['c'];
        yield ['r'];
        yield ['e'];
        yield ['T'];
    }
}
