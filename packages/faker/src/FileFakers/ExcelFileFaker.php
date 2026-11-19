<?php
namespace Apie\Faker\FileFakers;

use Apie\Faker\Interfaces\ApieFileFaker;
use Apie\Faker\Tabular\ColumnType;
use Apie\Faker\Tabular\RandomRecords;
use DateTimeImmutable;
use Faker\Generator;
use ZipStream\ZipStream;

/**
 * Generates a xlsx file with a random set of columns, a header row and random records in a randomly picked style.
 */
final class ExcelFileFaker implements ApieFileFaker
{
    private const STYLE_COUNT_OFFSET = 2;

    private const WIDTHS = [
        'Name' => 24,
        'Email' => 32,
        'City' => 20,
        'Company' => 28,
        'Country' => 18,
        'Phone' => 20,
        'Quantity' => 12,
        'Age' => 8,
        'Price' => 14,
        'Rating' => 10,
        'Created at' => 14,
        'Birthday' => 14,
    ];

    /** @var list<array{headerFill: string, headerFont: string, font: string, size: int, zebra: ?string, border: string}> */
    private const STYLES = [
        ['headerFill' => 'FF1F4E78', 'headerFont' => 'FFFFFFFF', 'font' => 'Calibri', 'size' => 11, 'zebra' => 'FFDDEBF7', 'border' => 'FFBFBFBF'],
        ['headerFill' => 'FF375623', 'headerFont' => 'FFFFFFFF', 'font' => 'Arial', 'size' => 10, 'zebra' => 'FFE2EFDA', 'border' => 'FFA9D08E'],
        ['headerFill' => 'FFC00000', 'headerFont' => 'FFFFFFFF', 'font' => 'Verdana', 'size' => 10, 'zebra' => null, 'border' => 'FF7F7F7F'],
        ['headerFill' => 'FFFFE699', 'headerFont' => 'FF000000', 'font' => 'Cambria', 'size' => 12, 'zebra' => 'FFFFF2CC', 'border' => 'FFBF8F00'],
        ['headerFill' => 'FF404040', 'headerFont' => 'FFFFFFFF', 'font' => 'Consolas', 'size' => 10, 'zebra' => 'FFF2F2F2', 'border' => 'FF404040'],
        ['headerFill' => 'FF7030A0', 'headerFont' => 'FFFFFFFF', 'font' => 'Calibri', 'size' => 11, 'zebra' => null, 'border' => 'FFD9D2E9'],
    ];

    public function createOriginalFilename(Generator $faker): string
    {
        return $faker->word() . '.xlsx';
    }

    public function createMimeType(): string
    {
        return 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    }

    public static function isSupported(): bool
    {
        return true;
    }

    /** @return resource */
    public function createResource(Generator $faker, string $originalFilename, string $mimeType): mixed
    {
        $records = RandomRecords::create($faker);
        $style = $faker->randomElement(self::STYLES);

        $resource = tmpfile();
        $zipstream = new ZipStream(
            outputStream: $resource,
            sendHttpHeaders: false,
            outputName: $originalFilename,
            contentType: $mimeType,
            defaultEnableZeroHeader: false,
            enableZip64: false,
        );
        $zipstream->addFile('[Content_Types].xml', $this->contentTypes());
        $zipstream->addFile('_rels/.rels', $this->rootRels());
        $zipstream->addFile('xl/workbook.xml', $this->workbook());
        $zipstream->addFile('xl/_rels/workbook.xml.rels', $this->workbookRels());
        $zipstream->addFile('xl/styles.xml', $this->styles($style));
        $zipstream->addFile('xl/worksheets/sheet1.xml', $this->sheet($records, $style['zebra'] !== null));
        $zipstream->finish();

        return $resource;
    }

    private const XML = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';

    private function contentTypes(): string
    {
        return self::XML
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            . '</Types>';
    }

    private function rootRels(): string
    {
        return self::XML
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '</Relationships>';
    }

    private function workbook(): string
    {
        return self::XML
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets><sheet name="Sheet1" sheetId="1" r:id="rId1"/></sheets>'
            . '</workbook>';
    }

    private function workbookRels(): string
    {
        return self::XML
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            . '</Relationships>';
    }

    /**
     * Cell format 0 is the default, 1 the header. After that: 4 types for normal rows followed by 4 types for zebra rows.
     *
     * @param array{headerFill: string, headerFont: string, font: string, size: int, zebra: ?string, border: string} $style
     */
    private function styles(array $style): string
    {
        // number formats per column type: general, #,##0, #,##0.00, custom date
        $numFmtIds = [
            ColumnType::Text->value => 0,
            ColumnType::Integer->value => 3,
            ColumnType::Decimal->value => 4,
            ColumnType::Date->value => 164,
        ];
        $xfs = '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
            . '<xf numFmtId="0" fontId="1" fillId="2" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>';
        foreach ([0, 1] as $zebra) {
            foreach ($numFmtIds as $type => $numFmtId) {
                $alignment = $type === ColumnType::Text->value ? '' : '<alignment horizontal="right"/>';
                $xfs .= '<xf numFmtId="' . $numFmtId . '" fontId="0" fillId="' . ($zebra && $style['zebra'] ? 3 : 0)
                    . '" borderId="1" xfId="0" applyNumberFormat="1" applyFont="1" applyFill="1" applyBorder="1"'
                    . ($alignment === '' ? '/>' : ' applyAlignment="1">' . $alignment . '</xf>');
            }
        }
        $font = htmlspecialchars($style['font'], ENT_XML1);
        $border = '<color rgb="' . $style['border'] . '"/>';

        return self::XML
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<numFmts count="1"><numFmt numFmtId="164" formatCode="yyyy\-mm\-dd"/></numFmts>'
            . '<fonts count="2">'
            . '<font><sz val="' . $style['size'] . '"/><name val="' . $font . '"/></font>'
            . '<font><b/><sz val="' . $style['size'] . '"/><color rgb="' . $style['headerFont'] . '"/><name val="' . $font . '"/></font>'
            . '</fonts>'
            . '<fills count="4">'
            . '<fill><patternFill patternType="none"/></fill>'
            . '<fill><patternFill patternType="gray125"/></fill>'
            . '<fill><patternFill patternType="solid"><fgColor rgb="' . $style['headerFill'] . '"/><bgColor indexed="64"/></patternFill></fill>'
            . '<fill><patternFill patternType="solid"><fgColor rgb="' . ($style['zebra'] ?? 'FFFFFFFF') . '"/><bgColor indexed="64"/></patternFill></fill>'
            . '</fills>'
            . '<borders count="2">'
            . '<border><left/><right/><top/><bottom/><diagonal/></border>'
            . '<border><left style="thin">' . $border . '</left><right style="thin">' . $border . '</right>'
            . '<top style="thin">' . $border . '</top><bottom style="thin">' . $border . '</bottom><diagonal/></border>'
            . '</borders>'
            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            . '<cellXfs count="' . (self::STYLE_COUNT_OFFSET + 8) . '">' . $xfs . '</cellXfs>'
            . '<cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles>'
            . '</styleSheet>';
    }

    private function sheet(RandomRecords $records, bool $zebra): string
    {
        $columns = $records->getColumns();
        $cols = '';
        $data = '<row r="1" ht="22" customHeight="1">';
        $index = 0;
        foreach ($columns as $column => $type) {
            $number = $index + 1;
            $cols .= '<col min="' . $number . '" max="' . $number . '" width="' . self::WIDTHS[$column] . '" customWidth="1"/>';
            $data .= $this->stringCell($this->cellReference($index, 1), $column, 1);
            $index++;
        }
        $data .= '</row>';
        $row = 2;
        foreach ($records as $record) {
            $offset = $zebra && $row % 2 === 1 ? 4 : 0;
            $data .= '<row r="' . $row . '">';
            $index = 0;
            foreach ($record as $column => $value) {
                $reference = $this->cellReference($index++, $row);
                $styleId = self::STYLE_COUNT_OFFSET + $offset + $columns[$column]->value;
                $data .= match (true) {
                    $value instanceof DateTimeImmutable => $this->numberCell($reference, $this->excelDate($value), $styleId),
                    is_string($value) => $this->stringCell($reference, $value, $styleId),
                    default => $this->numberCell($reference, $value, $styleId),
                };
            }
            $data .= '</row>';
            $row++;
        }
        $rows = $records->getRowCount();
        $lastColumn = $this->columnLetter(count($columns) - 1);

        return self::XML
            . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<dimension ref="A1:' . $lastColumn . ($rows + 1) . '"/>'
            . '<sheetViews><sheetView workbookViewId="0"><pane ySplit="1" topLeftCell="A2" activePane="bottomLeft" state="frozen"/></sheetView></sheetViews>'
            . '<sheetFormatPr defaultRowHeight="15"/>'
            . '<cols>' . $cols . '</cols>'
            . '<sheetData>' . $data . '</sheetData>'
            . '<autoFilter ref="A1:' . $lastColumn . ($rows + 1) . '"/>'
            . '</worksheet>';
    }

    private function excelDate(DateTimeImmutable $date): int
    {
        // days since 1899-12-30, which is the Excel epoch
        return intdiv($date->getTimestamp(), 86400) + 25569;
    }

    private function columnLetter(int $index): string
    {
        return chr(ord('A') + $index);
    }

    private function cellReference(int $column, int $row): string
    {
        return $this->columnLetter($column) . $row;
    }

    private function stringCell(string $reference, string $value, int $style): string
    {
        return '<c r="' . $reference . '" s="' . $style . '" t="inlineStr"><is><t>'
            . htmlspecialchars($value, ENT_XML1 | ENT_QUOTES | ENT_SUBSTITUTE)
            . '</t></is></c>';
    }

    private function numberCell(string $reference, int|float $value, int $style): string
    {
        return '<c r="' . $reference . '" s="' . $style . '"><v>' . $value . '</v></c>';
    }
}
