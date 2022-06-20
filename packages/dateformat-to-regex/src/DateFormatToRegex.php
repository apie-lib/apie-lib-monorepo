<?php
namespace Apie\DateformatToRegex;

use DateTime;
use DateTimeZone;

final class DateFormatToRegex
{
    // see https://www.php.net/manual/en/datetime.format.php
    private const FORMATS = [
        'd' => '((0[1-9])|([12][0-9])|(3[01]))', // days 01=>31
        'j' => '(([1-9])|([12][0-9])|(3[01]))', // days 1=>31
        'D' => '(Mon|Tue|Wed|Thu|Fri|Sat|Sun)', // mon-sun
        'l' => '(Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday)', // monday-sunday
        'N' => '[1-7]', // day of the week number 1-7
        'w' => '[0-6]', // day of the week number 0-6
        'S' => '(st|nd|rd|th)', // day suffix
        'z' => '([0-9]|[1-9][0-9]|[12][0-9]{2}|3[0-5][0-9]|36[0-5])',  // day of the year 0-365
        'W' =>  '(0[1-9]|[1-4][0-9]|5[0-3])', // week number 1-53
        'F' => '(January|February|March|April|May|June|July|August|September|October|November|December)', // january-december
        'M' => '(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)', //jan-dec
        'm' => '((0[1-9])|(1[0-2]))', // months 01=>12
        'n' => '([1-9]|1[0-2])', // months 1-12
        't' => '(28|29|30|31)',  //number of days in month
        'L' => '[01]', // is leap year
        'o' => '((|\-)[0-9]{4})', // year of current week
        'Y' => '((|\-)[0-9]{4})', // current year
        'y' => '[0-9]{2}', // year in 2 digits
        'a' => '(am|pm)', // am|pm
        'A' => '(AM|PM)', // AM|PM
        'B' => '[0-9]{3}', // swatch digit time
        'g' => '([1-9]|1[0-2])', // hours 1-12
        'h' => '((0[1-9])|(1[0-2]))', // hours 01-12
        'G' => '([0-9]|1[0-9]|2[0-3])', // hours 0-23
        'H' => '(([01][0-9])|(2[0-3]))', // hours 00-23
        'i' => '[0-5][0-9]', // minutes 00-59
        's' => '[0-5][0-9]', // minutes 00-59
        'u' => '[0-9]{6}', // microseconds
        'v' => '[0-9]{3}', // milliseconds
        'I' => '[01]', // 0-1 is DST
        'O' => '(\+\d{4})', // GMT difference, e.g. '+0200'
        'P' => '(\+\d{2}:\d{2})', // GMT difference, e.g. '+02:00'
        'p' => '(\+\d{2}:\d{2})|Z', // GMT difference, Z on +00:00
        'Z' => '(-[1-9]|-[1-3][0-9]{4}|-4[0-2][0-9]{3}|-43[01][0-9]{2}|-43200|-?[1-9][0-9]{1,3}|[0-9]|[1-4][0-9]{4}|50[0-3][0-9]{2}|50400)', // GMT difference in seconds: -43200-50400
        'U' => '((-|\+|)\d+)', // unix time stamp
        'T' => '(GMT|EST|MDT)(\+|-|)\d{4}'
    ];

    public static function formatToRegex(string $formatString, string $delimiter = '/'): string
    {
        $regex = self::createRegex($formatString, $delimiter);
        
        return $delimiter . '^' . $regex . '$' . $delimiter;
    }

    private static function createRegex(string $formatString, string $delimiter = '/'): string
    {
        $nextIsLiteral = false;
        $regex = '';
        foreach (str_split($formatString) as $character) {
            if ($nextIsLiteral) {
                $regex .= preg_quote($character, $delimiter);
                $nextIsLiteral = false;
                continue;
            }
            if ($character === '\\') {
                $nextIsLiteral = true;
                continue;
            }
            if ($character === 'c') {
                // https://www.php.net/manual/en/class.datetime.php#111532
                $regex .= self::createRegex('Y-m-d\TH:i:sP');
                continue;
            }
            if ($character === 'r') {
                $regex .= self::createRegex(DateTime::RFC2822);
                continue;
            }
            if ($character === 'e') { // timezone identifiers
                $regex .= self::createTimezoneIdentifierRegex($delimiter);
                continue;
            }
            if (isset(self::FORMATS[$character])) {
                $regex .= self::FORMATS[$character];
            } else {
                $regex .= preg_quote($character, $delimiter);
            }
        }
        return $regex;
    }

    public static function createTimezoneIdentifierRegex(string $delimiter = '/')
    {
        $regexes = [
            '(\+\d{2}:\d{2})',
        ];
        foreach (DateTimeZone::listIdentifiers() as $identifier) {
            $regexes[] = preg_quote($identifier, $delimiter);
        }
        return '(' . implode('|', $regexes) . ')';
    }
}
