<?php
namespace Apie\Faker\FileFakers;

use Apie\Faker\Interfaces\ApieFileFaker;
use Apie\Faker\Support\OpenDocumentWriter;
use Apie\Faker\Tabular\RandomRecords;
use DateTimeImmutable;
use Faker\Generator;

/**
 * Generates an OpenDocument text file with a title, headings, paragraphs, a list and a table in a random style.
 */
final class OdtFileFaker implements ApieFileFaker
{
    private const FONTS = ['Liberation Serif', 'Liberation Sans', 'DejaVu Sans', 'Courier New'];
    private const COLORS = ['#1F4E78', '#375623', '#C00000', '#7030A0', '#BF8F00', '#404040'];

    public function createOriginalFilename(Generator $faker): string
    {
        return $faker->word() . '.odt';
    }

    public function createMimeType(): string
    {
        return 'application/vnd.oasis.opendocument.text';
    }

    public static function isSupported(): bool
    {
        return true;
    }

    /** @return resource */
    public function createResource(Generator $faker, string $originalFilename, string $mimeType): mixed
    {
        $title = ucfirst($faker->words($faker->numberBetween(2, 5), true));

        return OpenDocumentWriter::write($mimeType, $originalFilename, $title, $this->createContent($faker, $title));
    }

    private function createContent(Generator $faker, string $title): string
    {
        $color = $faker->randomElement(self::COLORS);
        $font = $faker->randomElement(self::FONTS);
        $e = OpenDocumentWriter::escape(...);
        $body = '<text:p text:style-name="Title">' . $e($title) . '</text:p>';
        $sections = $faker->numberBetween(2, 4);
        $tableSection = $faker->numberBetween(0, $sections - 1);
        for ($i = 0; $i < $sections; $i++) {
            $body .= '<text:h text:style-name="Heading" text:outline-level="1">' . $e(ucfirst($faker->words(3, true))) . '</text:h>';
            for ($j = $faker->numberBetween(1, 3); $j > 0; $j--) {
                $body .= '<text:p text:style-name="Body">' . $e($faker->paragraph(5)) . '</text:p>';
            }
            if ($faker->boolean(50)) {
                $body .= '<text:list>';
                for ($j = $faker->numberBetween(3, 6); $j > 0; $j--) {
                    $body .= '<text:list-item><text:p text:style-name="Body">' . $e($faker->sentence(6)) . '</text:p></text:list-item>';
                }
                $body .= '</text:list>';
            }
            if ($i === $tableSection) {
                $body .= $this->createTable($faker, $e);
            }
        }

        return OpenDocumentWriter::XML
            . '<office:document-content ' . OpenDocumentWriter::NAMESPACES . ' office:version="1.2">'
            . '<office:automatic-styles>'
            . '<style:style style:name="Title" style:family="paragraph">'
            . '<style:paragraph-properties fo:text-align="center" fo:margin-bottom="0.5cm"/>'
            . '<style:text-properties fo:font-family="' . $font . '" fo:font-size="26pt" fo:font-weight="bold" fo:color="' . $color . '"/></style:style>'
            . '<style:style style:name="Heading" style:family="paragraph">'
            . '<style:paragraph-properties fo:margin-top="0.4cm" fo:margin-bottom="0.2cm"/>'
            . '<style:text-properties fo:font-family="' . $font . '" fo:font-size="16pt" fo:font-weight="bold" fo:color="' . $color . '"/></style:style>'
            . '<style:style style:name="Body" style:family="paragraph">'
            . '<style:paragraph-properties fo:margin-bottom="0.25cm" fo:text-align="justify"/>'
            . '<style:text-properties fo:font-family="' . $font . '" fo:font-size="11pt"/></style:style>'
            . '<style:style style:name="Table" style:family="table"><style:table-properties style:width="17cm" table:align="margins"/></style:style>'
            . '<style:style style:name="TableHeader" style:family="table-cell">'
            . '<style:table-cell-properties fo:background-color="' . $color . '" fo:padding="0.1cm" fo:border="0.5pt solid #999999"/>'
            . '<style:text-properties fo:font-weight="bold" fo:color="#ffffff"/></style:style>'
            . '<style:style style:name="TableCell" style:family="table-cell">'
            . '<style:table-cell-properties fo:padding="0.1cm" fo:border="0.5pt solid #999999"/></style:style>'
            . '</office:automatic-styles>'
            . '<office:body><office:text>' . $body . '</office:text></office:body>'
            . '</office:document-content>';
    }

    private function createTable(Generator $faker, \Closure $e): string
    {
        $records = RandomRecords::create($faker, 3, 10);
        $columns = $records->getColumnNames();
        $table = '<table:table table:name="Table1" table:style-name="Table">'
            . '<table:table-column table:number-columns-repeated="' . count($columns) . '"/><table:table-row>';
        foreach ($columns as $name) {
            $table .= '<table:table-cell table:style-name="TableHeader" office:value-type="string"><text:p>' . $e($name) . '</text:p></table:table-cell>';
        }
        $table .= '</table:table-row>';
        foreach ($records as $record) {
            $table .= '<table:table-row>';
            foreach ($record as $value) {
                $text = $value instanceof DateTimeImmutable ? $value->format('Y-m-d') : (string) $value;
                $table .= '<table:table-cell table:style-name="TableCell" office:value-type="string"><text:p>' . $e($text) . '</text:p></table:table-cell>';
            }
            $table .= '</table:table-row>';
        }

        return $table . '</table:table>';
    }
}
