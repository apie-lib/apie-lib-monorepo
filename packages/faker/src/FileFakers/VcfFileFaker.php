<?php
namespace Apie\Faker\FileFakers;

use Apie\Faker\Interfaces\ApieFileFaker;
use Apie\Faker\Support\ContentLines;
use Faker\Generator;
use Nyholm\Psr7\Stream;

/**
 * Generates a vCard 3.0 file containing a random number of contacts.
 */
final class VcfFileFaker implements ApieFileFaker
{
    public function createOriginalFilename(Generator $faker): string
    {
        return $faker->word() . '.vcf';
    }

    public function createMimeType(): string
    {
        return 'text/vcard';
    }

    public static function isSupported(): bool
    {
        return true;
    }

    /** @return resource */
    public function createResource(Generator $faker, string $originalFilename, string $mimeType): mixed
    {
        $lines = [];
        for ($i = $faker->numberBetween(1, 15); $i > 0; $i--) {
            $first = $faker->firstName();
            $last = $faker->lastName();
            $lines[] = 'BEGIN:VCARD';
            $lines[] = 'VERSION:3.0';
            $lines[] = 'N:' . ContentLines::escape($last) . ';' . ContentLines::escape($first) . ';;;';
            $lines[] = 'FN:' . ContentLines::escape($first . ' ' . $last);
            $lines[] = 'ORG:' . ContentLines::escape($faker->company());
            $lines[] = 'TITLE:' . ContentLines::escape($faker->jobTitle());
            $lines[] = 'TEL;TYPE=CELL:' . ContentLines::escape($faker->phoneNumber());
            $lines[] = 'EMAIL;TYPE=INTERNET:' . $faker->safeEmail();
            $lines[] = 'ADR;TYPE=HOME:;;' . implode(';', array_map(
                ContentLines::escape(...),
                [$faker->streetAddress(), $faker->city(), $faker->state(), $faker->postcode(), $faker->country()]
            ));
            if ($faker->boolean(50)) {
                $lines[] = 'BDAY:' . $faker->dateTimeBetween('-80 years', '-18 years')->format('Y-m-d');
            }
            if ($faker->boolean(40)) {
                $lines[] = 'URL:https://' . $faker->domainName();
            }
            if ($faker->boolean(30)) {
                $lines[] = 'NOTE:' . ContentLines::escape($faker->sentence(10));
            }
            $lines[] = 'REV:' . gmdate('Ymd\THis\Z');
            $lines[] = 'END:VCARD';
        }

        return Stream::create(ContentLines::render($lines))->detach();
    }
}
