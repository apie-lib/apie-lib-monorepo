<?php
namespace Apie\Tests\DateformatToRegex;

use Apie\DateformatToRegex\DateFormatToRegex;
use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class DateFormatToRegexTest extends TestCase
{
    private const FORMAT_CONSTANTS = [
        'd',
        'j',
        'D',
        'l',
        'N',
        'w',
        'S',
        'z',
        'W',
        'F',
        'M',
        'm',
        'n',
        't',
        'L',
        'o',
        'Y',
        'y',
        'a',
        'A',
        'B',
        'g',
        'h',
        'G',
        'H',
        'i',
        's',
        'u',
        'v',
        'I',
        'O',
        'P',
        'p',
        'Z',
        'U',
        'c',
        'r',
        'e',
        'T',
    ];
    #[\PHPUnit\Framework\Attributes\DataProvider('formatProvider')]
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

    public static function formatProvider()
    {
        yield 'ATOM format' => [DateTime::ATOM];
        yield 'Cookie format' => [DateTime::COOKIE];
        yield 'ISO8601 format' => [DateTime::ISO8601];
        yield 'RFC1036 format' => [DateTime::RFC1036];
        yield 'RFC1036 extended format' => [DateTime::RFC3339_EXTENDED];
        foreach (self::FORMAT_CONSTANTS as $constant) {
            $key = 'format "' . $constant . '"';
            yield $key => [$constant];
        }
    }
}
