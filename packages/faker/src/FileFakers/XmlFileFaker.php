<?php
namespace Apie\Faker\FileFakers;

use Apie\Faker\Interfaces\ApieFileFaker;
use Apie\Faker\Tabular\RandomRecords;
use DateTimeImmutable;
use Faker\Generator;
use Nyholm\Psr7\Stream;
use XMLWriter;

/**
 * Generates an xml file where every random record is an element and every column name is a child tag.
 */
final class XmlFileFaker implements ApieFileFaker
{
    private const ELEMENT_NAMES = [
        ['records', 'record'],
        ['rows', 'row'],
        ['items', 'item'],
        ['entries', 'entry'],
        ['dataset', 'data'],
    ];

    public function createOriginalFilename(Generator $faker): string
    {
        return $faker->word() . '.xml';
    }

    public function createMimeType(): string
    {
        return 'application/xml';
    }

    public static function isSupported(): bool
    {
        return extension_loaded('xmlwriter');
    }

    /** @return resource */
    public function createResource(Generator $faker, string $originalFilename, string $mimeType): mixed
    {
        [$rootName, $recordName] = $faker->randomElement(self::ELEMENT_NAMES);
        $records = RandomRecords::create($faker, 5, 100);

        $writer = new XMLWriter();
        $writer->openMemory();
        $writer->setIndent(true);
        $writer->setIndentString('  ');
        $writer->startDocument('1.0', 'UTF-8');
        $writer->startElement($rootName);
        foreach ($records as $record) {
            $writer->startElement($recordName);
            foreach ($record as $column => $value) {
                $writer->startElement($this->toTagName($column));
                $writer->text($value instanceof DateTimeImmutable ? $value->format('Y-m-d') : (string) $value);
                $writer->endElement();
            }
            $writer->endElement();
        }
        $writer->endElement();
        $writer->endDocument();

        return Stream::create($writer->outputMemory())->detach();
    }

    /** Column names can contain spaces, which are not allowed in xml tag names. */
    private function toTagName(string $column): string
    {
        return strtolower(str_replace(' ', '_', $column));
    }
}
