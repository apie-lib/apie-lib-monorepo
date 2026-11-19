<?php
namespace Apie\Faker\FileFakers;

use Apie\Faker\Interfaces\ApieFileFaker;
use Apie\Faker\Support\OpenDocumentWriter;
use Apie\Faker\Tabular\RandomRecords;
use DateTimeImmutable;
use Faker\Generator;

/**
 * Generates an OpenDocument spreadsheet with a styled header row and typed cells (text, numbers and dates).
 */
final class OdsFileFaker implements ApieFileFaker
{
    private const HEADER_COLORS = ['#1F4E78', '#375623', '#C00000', '#7030A0', '#BF8F00', '#404040'];
    private const FONTS = ['Liberation Sans', 'Liberation Serif', 'DejaVu Sans', 'Courier New'];

    public function createOriginalFilename(Generator $faker): string
    {
        return $faker->word() . '.ods';
    }

    public function createMimeType(): string
    {
        return 'application/vnd.oasis.opendocument.spreadsheet';
    }

    public static function isSupported(): bool
    {
        return true;
    }

    /** @return resource */
    public function createResource(Generator $faker, string $originalFilename, string $mimeType): mixed
    {
        return OpenDocumentWriter::write(
            $mimeType,
            $originalFilename,
            ucfirst($faker->words(3, true)),
            $this->createContent($faker)
        );
    }

    private function createContent(Generator $faker): string
    {
        $records = RandomRecords::create($faker);
        $e = OpenDocumentWriter::escape(...);
        $header = $faker->randomElement(self::HEADER_COLORS);
        $font = $faker->randomElement(self::FONTS);
        $zebra = $faker->boolean(50);

        $rows = '<table:table-row>';
        foreach ($records->getColumnNames() as $name) {
            $rows .= '<table:table-cell table:style-name="Header" office:value-type="string"><text:p>' . $e($name) . '</text:p></table:table-cell>';
        }
        $rows .= '</table:table-row>';
        foreach ($records as $index => $record) {
            $suffix = $zebra && $index % 2 === 1 ? 'Zebra' : '';
            $rows .= '<table:table-row>';
            foreach ($record as $value) {
                $rows .= match (true) {
                    $value instanceof DateTimeImmutable => '<table:table-cell table:style-name="Date' . $suffix . '" office:value-type="date" office:date-value="'
                        . $value->format('Y-m-d') . '"><text:p>' . $value->format('Y-m-d') . '</text:p></table:table-cell>',
                    is_string($value) => '<table:table-cell table:style-name="Cell' . $suffix . '" office:value-type="string"><text:p>' . $e($value) . '</text:p></table:table-cell>',
                    default => '<table:table-cell table:style-name="Cell' . $suffix . '" office:value-type="float" office:value="' . $value . '"><text:p>' . $value . '</text:p></table:table-cell>',
                };
            }
            $rows .= '</table:table-row>';
        }

        $cellProperties = fn (string $background) => '<style:table-cell-properties fo:background-color="' . $background . '" fo:border="0.5pt solid #bfbfbf"/>'
            . '<style:text-properties fo:font-family="' . $font . '"/>';

        return OpenDocumentWriter::XML
            . '<office:document-content ' . OpenDocumentWriter::NAMESPACES . ' office:version="1.2">'
            . '<office:automatic-styles>'
            . '<style:style style:name="Col" style:family="table-column"><style:table-column-properties style:column-width="4cm"/></style:style>'
            . '<number:date-style style:name="NDate"><number:year number:style="long"/><number:text>-</number:text>'
            . '<number:month number:style="long"/><number:text>-</number:text><number:day number:style="long"/></number:date-style>'
            . '<style:style style:name="Header" style:family="table-cell"><style:table-cell-properties fo:background-color="' . $header . '" fo:border="0.5pt solid #bfbfbf"/>'
            . '<style:text-properties fo:font-family="' . $font . '" fo:font-weight="bold" fo:color="#ffffff"/></style:style>'
            . '<style:style style:name="Cell" style:family="table-cell">' . $cellProperties('transparent') . '</style:style>'
            . '<style:style style:name="CellZebra" style:family="table-cell">' . $cellProperties('#f2f2f2') . '</style:style>'
            . '<style:style style:name="Date" style:family="table-cell" style:data-style-name="NDate">' . $cellProperties('transparent') . '</style:style>'
            . '<style:style style:name="DateZebra" style:family="table-cell" style:data-style-name="NDate">' . $cellProperties('#f2f2f2') . '</style:style>'
            . '</office:automatic-styles>'
            . '<office:body><office:spreadsheet><table:table table:name="Sheet1">'
            . '<table:table-column table:style-name="Col" table:number-columns-repeated="' . count($records->getColumnNames()) . '"/>'
            . $rows
            . '</table:table></office:spreadsheet></office:body>'
            . '</office:document-content>';
    }
}
