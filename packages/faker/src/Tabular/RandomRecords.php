<?php
namespace Apie\Faker\Tabular;

use DateTimeImmutable;
use Faker\Generator;
use IteratorAggregate;
use Traversable;

/**
 * Iterates over a random number of fake records with a random set of columns.
 * The columns are chosen at construction, the records are generated lazily while iterating.
 *
 * @implements IteratorAggregate<int, array<string, string|int|float|DateTimeImmutable>>
 */
final class RandomRecords implements IteratorAggregate
{
    /** @var array<string, ColumnType> */
    private const COLUMNS = [
        'Name' => ColumnType::Text,
        'Email' => ColumnType::Text,
        'City' => ColumnType::Text,
        'Company' => ColumnType::Text,
        'Country' => ColumnType::Text,
        'Phone' => ColumnType::Text,
        'Quantity' => ColumnType::Integer,
        'Age' => ColumnType::Integer,
        'Price' => ColumnType::Decimal,
        'Rating' => ColumnType::Decimal,
        'Created at' => ColumnType::Date,
        'Birthday' => ColumnType::Date,
    ];

    /** @var array<int, string> */
    private array $columnNames;

    public function __construct(
        private readonly Generator $faker,
        private readonly int $rowCount,
        int $minColumns = 2,
        int $maxColumns = 8
    ) {
        $this->columnNames = $faker->randomElements(
            array_keys(self::COLUMNS),
            $faker->numberBetween($minColumns, min($maxColumns, count(self::COLUMNS)))
        );
    }

    public static function create(Generator $faker, int $minRows = 5, int $maxRows = 60): self
    {
        return new self($faker, $faker->numberBetween($minRows, $maxRows));
    }

    /** @return array<int, string> */
    public function getColumnNames(): array
    {
        return $this->columnNames;
    }

    /** @return array<string, ColumnType> */
    public function getColumns(): array
    {
        $result = [];
        foreach ($this->columnNames as $name) {
            $result[$name] = self::COLUMNS[$name];
        }
        return $result;
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    /** @return Traversable<int, array<string, string|int|float|DateTimeImmutable>> */
    public function getIterator(): Traversable
    {
        for ($i = 0; $i < $this->rowCount; $i++) {
            $record = [];
            foreach ($this->columnNames as $name) {
                $record[$name] = $this->fakeValue($name);
            }
            yield $i => $record;
        }
    }

    private function fakeValue(string $column): string|int|float|DateTimeImmutable
    {
        $faker = $this->faker;
        return match ($column) {
            'Name' => $faker->name(),
            'Email' => $faker->safeEmail(),
            'City' => $faker->city(),
            'Company' => $faker->company(),
            'Country' => $faker->country(),
            'Phone' => $faker->phoneNumber(),
            'Quantity' => $faker->numberBetween(1, 5000),
            'Age' => $faker->numberBetween(18, 90),
            'Price' => $faker->randomFloat(2, 1, 2500),
            'Rating' => $faker->randomFloat(1, 1, 10),
            'Birthday' => DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-80 years', '-18 years')),
            default => DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-3 years')),
        };
    }
}
