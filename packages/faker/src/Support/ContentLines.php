<?php
namespace Apie\Faker\Support;

/**
 * Helpers for the line based text formats iCalendar and vCard (RFC 5545 and RFC 2426).
 */
final class ContentLines
{
    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    public static function escape(string $value): string
    {
        return str_replace(['\\', "\r\n", "\n", ',', ';'], ['\\\\', '\n', '\n', '\,', '\;'], $value);
    }

    /** Lines should not be longer than 75 octets, longer lines continue on the next line starting with a space. */
    public static function fold(string $line): string
    {
        $parts = [];
        $limit = 75;
        while (strlen($line) > $limit) {
            $chunk = mb_strcut($line, 0, $limit, 'UTF-8');
            $parts[] = $chunk;
            $line = substr($line, strlen($chunk));
            $limit = 74;
        }
        $parts[] = $line;

        return implode("\r\n ", $parts);
    }

    /** @param array<int, string> $lines */
    public static function render(array $lines): string
    {
        return implode('', array_map(fn (string $line) => self::fold($line) . "\r\n", $lines));
    }
}
