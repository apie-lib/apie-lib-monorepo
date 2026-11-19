<?php
namespace Apie\Faker\FileFakers;

use Apie\Faker\Interfaces\ApieFileFaker;
use Apie\Faker\Support\ContentLines;
use DateTimeImmutable;
use DateTimeZone;
use Faker\Generator;
use Nyholm\Psr7\Stream;

/**
 * Generates an iCalendar file with a random set of events, some all day, recurring or with a reminder.
 */
final class IcsFileFaker implements ApieFileFaker
{
    public function createOriginalFilename(Generator $faker): string
    {
        return $faker->word() . '.ics';
    }

    public function createMimeType(): string
    {
        return 'text/calendar';
    }

    public static function isSupported(): bool
    {
        return true;
    }

    /** @return resource */
    public function createResource(Generator $faker, string $originalFilename, string $mimeType): mixed
    {
        $utc = new DateTimeZone('UTC');
        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//' . ContentLines::escape($faker->company()) . '//Apie faker//EN',
            'CALSCALE:GREGORIAN',
            'METHOD:PUBLISH',
            'X-WR-CALNAME:' . ContentLines::escape(ucfirst($faker->words(2, true))),
        ];
        $stamp = (new DateTimeImmutable('now', $utc))->format('Ymd\THis\Z');
        for ($i = $faker->numberBetween(3, 12); $i > 0; $i--) {
            $start = DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 month', '+1 year'))->setTimezone($utc);
            $lines[] = 'BEGIN:VEVENT';
            $lines[] = 'UID:' . $faker->uuid() . '@' . $faker->domainName();
            $lines[] = 'DTSTAMP:' . $stamp;
            if ($faker->boolean(20)) {
                $days = $faker->numberBetween(1, 3);
                $lines[] = 'DTSTART;VALUE=DATE:' . $start->format('Ymd');
                $lines[] = 'DTEND;VALUE=DATE:' . $start->modify('+' . $days . ' days')->format('Ymd');
            } else {
                $lines[] = 'DTSTART:' . $start->format('Ymd\THis\Z');
                $lines[] = 'DTEND:' . $start->modify('+' . $faker->numberBetween(30, 240) . ' minutes')->format('Ymd\THis\Z');
            }
            $lines[] = 'SUMMARY:' . ContentLines::escape(ucfirst($faker->words(3, true)));
            $lines[] = 'LOCATION:' . ContentLines::escape($faker->address());
            $lines[] = 'DESCRIPTION:' . ContentLines::escape($faker->sentence(14));
            $lines[] = 'STATUS:' . $faker->randomElement(['CONFIRMED', 'TENTATIVE']);
            $lines[] = 'ORGANIZER;CN="' . str_replace('"', '', $faker->name()) . '":mailto:' . $faker->safeEmail();
            if ($faker->boolean(25)) {
                $lines[] = 'RRULE:FREQ=' . $faker->randomElement(['DAILY', 'WEEKLY', 'MONTHLY']) . ';COUNT=' . $faker->numberBetween(2, 10);
            }
            if ($faker->boolean(30)) {
                $lines[] = 'BEGIN:VALARM';
                $lines[] = 'ACTION:DISPLAY';
                $lines[] = 'DESCRIPTION:Reminder';
                $lines[] = 'TRIGGER:-PT' . $faker->randomElement([5, 15, 30, 60]) . 'M';
                $lines[] = 'END:VALARM';
            }
            $lines[] = 'END:VEVENT';
        }
        $lines[] = 'END:VCALENDAR';

        return Stream::create(ContentLines::render($lines))->detach();
    }
}
