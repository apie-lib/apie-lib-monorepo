<?php
namespace Apie\Faker\FileFakers;

use Apie\Faker\Interfaces\ApieFileFaker;
use Apie\Faker\Tabular\RandomRecords;
use DateTimeImmutable;
use Faker\Generator;

/**
 * Generates a csv file with a header row and random records. The delimiter is random.
 */
final class CsvFileFaker implements ApieFileFaker
{
    public function createOriginalFilename(Generator $faker): string
    {
        return $faker->word() . '.csv';
    }

    public function createMimeType(): string
    {
        return 'text/csv';
    }

    public static function isSupported(): bool
    {
        return true;
    }

    /** @return resource */
    public function createResource(Generator $faker, string $originalFilename, string $mimeType): mixed
    {
        $delimiter = $faker->randomElement([',', ';', "\t"]);
        $records = RandomRecords::create($faker, 5, 200);
        $resource = tmpfile();
        fputcsv($resource, $records->getColumnNames(), $delimiter, '"', '\\', "\r\n");
        foreach ($records as $record) {
            fputcsv(
                $resource,
                array_map(
                    fn (string|int|float|DateTimeImmutable $value) => $value instanceof DateTimeImmutable
                        ? $value->format('Y-m-d')
                        : $value,
                    $record
                ),
                $delimiter,
                '"',
                '\\',
                "\r\n"
            );
        }
        rewind($resource);

        return $resource;
    }
}
