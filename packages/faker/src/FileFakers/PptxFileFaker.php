<?php
namespace Apie\Faker\FileFakers;

use Apie\Faker\Interfaces\ApieFileFaker;
use Faker\Generator;
use ZipStream\ZipStream;

/**
 * Generates a pptx presentation: a title slide, a random number of bullet and big number slides and a closing slide.
 * The master and layout are minimal and every slide uses explicit positions and colors from a random palette.
 */
final class PptxFileFaker implements ApieFileFaker
{
    private const XML = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
    private const NS_A = 'http://schemas.openxmlformats.org/drawingml/2006/main';
    private const NS_R = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships';
    private const NS_P = 'http://schemas.openxmlformats.org/presentationml/2006/main';
    private const REL = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/';
    private const CT = 'application/vnd.openxmlformats-officedocument.';

    private const WIDTH = 12192000;
    private const HEIGHT = 6858000;

    /** @var list<array{dark: string, accent: string, light: string, text: string}> */
    private const PALETTES = [
        ['dark' => '1F2A44', 'accent' => 'F9A826', 'light' => 'F5F7FA', 'text' => '1F2A44'],
        ['dark' => '12372A', 'accent' => '63B995', 'light' => 'F4F9F6', 'text' => '12372A'],
        ['dark' => '3C1642', 'accent' => 'FF6F91', 'light' => 'FBF5FC', 'text' => '3C1642'],
        ['dark' => '0B3C5D', 'accent' => '328CC1', 'light' => 'F2F8FC', 'text' => '0B3C5D'],
        ['dark' => '2B2B2B', 'accent' => 'E63946', 'light' => 'FAFAFA', 'text' => '2B2B2B'],
    ];

    public function createOriginalFilename(Generator $faker): string
    {
        return $faker->word() . '.pptx';
    }

    public function createMimeType(): string
    {
        return 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
    }

    public static function isSupported(): bool
    {
        return true;
    }

    /** @return resource */
    public function createResource(Generator $faker, string $originalFilename, string $mimeType): mixed
    {
        $palette = $faker->randomElement(self::PALETTES);
        $slides = $this->createSlides($faker, $palette);

        $resource = tmpfile();
        $zipstream = new ZipStream(
            outputStream: $resource,
            sendHttpHeaders: false,
            outputName: $originalFilename,
            contentType: $mimeType,
            defaultEnableZeroHeader: false,
            enableZip64: false,
        );
        $zipstream->addFile('[Content_Types].xml', $this->contentTypes(count($slides)));
        $zipstream->addFile('_rels/.rels', $this->relationships([['rId1', 'officeDocument', 'ppt/presentation.xml']]));
        $zipstream->addFile('ppt/presentation.xml', $this->presentation(count($slides)));
        $presentationRels = [['rId1', 'slideMaster', 'slideMasters/slideMaster1.xml']];
        foreach (array_keys($slides) as $index) {
            $presentationRels[] = ['rId' . ($index + 2), 'slide', 'slides/slide' . ($index + 1) . '.xml'];
        }
        $presentationRels[] = ['rId' . (count($slides) + 2), 'theme', 'theme/theme1.xml'];
        $zipstream->addFile('ppt/_rels/presentation.xml.rels', $this->relationships($presentationRels));
        $zipstream->addFile('ppt/slideMasters/slideMaster1.xml', $this->slideMaster());
        $zipstream->addFile('ppt/slideMasters/_rels/slideMaster1.xml.rels', $this->relationships([
            ['rId1', 'slideLayout', '../slideLayouts/slideLayout1.xml'],
            ['rId2', 'theme', '../theme/theme1.xml'],
        ]));
        $zipstream->addFile('ppt/slideLayouts/slideLayout1.xml', $this->slideLayout());
        $zipstream->addFile('ppt/slideLayouts/_rels/slideLayout1.xml.rels', $this->relationships([
            ['rId1', 'slideMaster', '../slideMasters/slideMaster1.xml'],
        ]));
        $zipstream->addFile('ppt/theme/theme1.xml', $this->theme($palette));
        foreach ($slides as $index => $slide) {
            $zipstream->addFile('ppt/slides/slide' . ($index + 1) . '.xml', $slide);
            $zipstream->addFile(
                'ppt/slides/_rels/slide' . ($index + 1) . '.xml.rels',
                $this->relationships([['rId1', 'slideLayout', '../slideLayouts/slideLayout1.xml']])
            );
        }
        $zipstream->finish();

        return $resource;
    }

    /**
     * @param array{dark: string, accent: string, light: string, text: string} $palette
     * @return array<int, string>
     */
    private function createSlides(Generator $faker, array $palette): array
    {
        $slides = [$this->titleSlide($faker, $palette)];
        for ($i = $faker->numberBetween(2, 6); $i > 0; $i--) {
            $slides[] = $faker->boolean(25) ? $this->numberSlide($faker, $palette) : $this->bulletSlide($faker, $palette);
        }
        $slides[] = $this->closingSlide($faker, $palette);

        return $slides;
    }

    /** @param array{dark: string, accent: string, light: string, text: string} $palette */
    private function titleSlide(Generator $faker, array $palette): string
    {
        return $this->slide($palette['dark'], [
            $this->rectangle(2, 0, 4800000, self::WIDTH, 120000, $palette['accent']),
            $this->textBox(3, 900000, 1800000, 10400000, 1800000, [rtrim($faker->catchPhrase(), '.')], 5000, true, 'FFFFFF'),
            $this->textBox(4, 900000, 3900000, 10400000, 700000, [$faker->company() . ' - ' . $faker->name()], 2400, false, $palette['accent']),
        ]);
    }

    /** @param array{dark: string, accent: string, light: string, text: string} $palette */
    private function bulletSlide(Generator $faker, array $palette): string
    {
        $bullets = [];
        for ($i = $faker->numberBetween(3, 5); $i > 0; $i--) {
            $bullets[] = rtrim($faker->sentence(8), '.');
        }

        return $this->slide($palette['light'], [
            $this->rectangle(2, 0, 0, 300000, self::HEIGHT, $palette['accent']),
            $this->textBox(3, 800000, 400000, 10600000, 1000000, [ucfirst($faker->words(3, true))], 3600, true, $palette['dark']),
            $this->textBox(4, 800000, 1600000, 10600000, 4600000, $bullets, 2400, false, $palette['text'], bullets: true),
        ]);
    }

    /** @param array{dark: string, accent: string, light: string, text: string} $palette */
    private function numberSlide(Generator $faker, array $palette): string
    {
        return $this->slide($palette['accent'], [
            $this->textBox(2, 900000, 1500000, 10400000, 2400000, [number_format($faker->numberBetween(10, 99999))], 12000, true, 'FFFFFF', 'ctr'),
            $this->textBox(3, 900000, 4100000, 10400000, 1000000, [ucfirst($faker->words(4, true))], 3000, false, $palette['dark'], 'ctr'),
        ]);
    }

    /** @param array{dark: string, accent: string, light: string, text: string} $palette */
    private function closingSlide(Generator $faker, array $palette): string
    {
        return $this->slide($palette['dark'], [
            $this->textBox(2, 900000, 2300000, 10400000, 1400000, ['Thank you'], 6000, true, 'FFFFFF', 'ctr'),
            $this->textBox(3, 900000, 3900000, 10400000, 700000, [$faker->safeEmail()], 2400, false, $palette['accent'], 'ctr'),
        ]);
    }

    /** @param array<int, string> $shapes */
    private function slide(string $background, array $shapes): string
    {
        return self::XML
            . '<p:sld xmlns:a="' . self::NS_A . '" xmlns:r="' . self::NS_R . '" xmlns:p="' . self::NS_P . '">'
            . '<p:cSld><p:bg><p:bgPr><a:solidFill><a:srgbClr val="' . $background . '"/></a:solidFill><a:effectLst/></p:bgPr></p:bg>'
            . '<p:spTree>' . $this->groupProperties() . implode('', $shapes) . '</p:spTree></p:cSld>'
            . '<p:clrMapOvr><a:masterClrMapping/></p:clrMapOvr></p:sld>';
    }

    private function groupProperties(): string
    {
        return '<p:nvGrpSpPr><p:cNvPr id="1" name=""/><p:cNvGrpSpPr/><p:nvPr/></p:nvGrpSpPr>'
            . '<p:grpSpPr><a:xfrm><a:off x="0" y="0"/><a:ext cx="0" cy="0"/><a:chOff x="0" y="0"/><a:chExt cx="0" cy="0"/></a:xfrm></p:grpSpPr>';
    }

    private function rectangle(int $id, int $x, int $y, int $width, int $height, string $color): string
    {
        return '<p:sp><p:nvSpPr><p:cNvPr id="' . $id . '" name="Shape ' . $id . '"/><p:cNvSpPr/><p:nvPr/></p:nvSpPr>'
            . '<p:spPr><a:xfrm><a:off x="' . $x . '" y="' . $y . '"/><a:ext cx="' . $width . '" cy="' . $height . '"/></a:xfrm>'
            . '<a:prstGeom prst="rect"><a:avLst/></a:prstGeom><a:solidFill><a:srgbClr val="' . $color . '"/></a:solidFill><a:ln><a:noFill/></a:ln></p:spPr></p:sp>';
    }

    /** @param array<int, string> $paragraphs */
    private function textBox(
        int $id,
        int $x,
        int $y,
        int $width,
        int $height,
        array $paragraphs,
        int $size,
        bool $bold,
        string $color,
        string $align = 'l',
        bool $bullets = false
    ): string {
        $text = '';
        foreach ($paragraphs as $paragraph) {
            $text .= '<a:p><a:pPr algn="' . $align . '"'
                . ($bullets ? ' marL="342900" indent="-342900"' : '') . '>'
                . ($bullets ? '<a:spcBef><a:spcPts val="1200"/></a:spcBef><a:buFont typeface="Arial"/><a:buChar char="&#8226;"/>' : '<a:buNone/>')
                . '</a:pPr><a:r><a:rPr lang="en-US" sz="' . $size . '" b="' . ($bold ? 1 : 0) . '" dirty="0">'
                . '<a:solidFill><a:srgbClr val="' . $color . '"/></a:solidFill></a:rPr>'
                . '<a:t>' . htmlspecialchars($paragraph, ENT_XML1 | ENT_QUOTES | ENT_SUBSTITUTE) . '</a:t></a:r></a:p>';
        }

        return '<p:sp><p:nvSpPr><p:cNvPr id="' . $id . '" name="TextBox ' . $id . '"/><p:cNvSpPr txBox="1"/><p:nvPr/></p:nvSpPr>'
            . '<p:spPr><a:xfrm><a:off x="' . $x . '" y="' . $y . '"/><a:ext cx="' . $width . '" cy="' . $height . '"/></a:xfrm>'
            . '<a:prstGeom prst="rect"><a:avLst/></a:prstGeom><a:noFill/></p:spPr>'
            . '<p:txBody><a:bodyPr wrap="square" rtlCol="0" anchor="ctr"><a:normAutofit/></a:bodyPr><a:lstStyle/>' . $text . '</p:txBody></p:sp>';
    }

    private function contentTypes(int $slideCount): string
    {
        $slides = '';
        for ($i = 1; $i <= $slideCount; $i++) {
            $slides .= '<Override PartName="/ppt/slides/slide' . $i . '.xml" ContentType="' . self::CT . 'presentationml.slide+xml"/>';
        }

        return self::XML
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/ppt/presentation.xml" ContentType="' . self::CT . 'presentationml.presentation.main+xml"/>'
            . '<Override PartName="/ppt/slideMasters/slideMaster1.xml" ContentType="' . self::CT . 'presentationml.slideMaster+xml"/>'
            . '<Override PartName="/ppt/slideLayouts/slideLayout1.xml" ContentType="' . self::CT . 'presentationml.slideLayout+xml"/>'
            . '<Override PartName="/ppt/theme/theme1.xml" ContentType="' . self::CT . 'theme+xml"/>'
            . $slides
            . '</Types>';
    }

    /** @param array<int, array{string, string, string}> $relationships */
    private function relationships(array $relationships): string
    {
        $xml = self::XML . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">';
        foreach ($relationships as [$id, $type, $target]) {
            $xml .= '<Relationship Id="' . $id . '" Type="' . self::REL . $type . '" Target="' . $target . '"/>';
        }

        return $xml . '</Relationships>';
    }

    private function presentation(int $slideCount): string
    {
        $slides = '';
        for ($i = 1; $i <= $slideCount; $i++) {
            $slides .= '<p:sldId id="' . (255 + $i) . '" r:id="rId' . ($i + 1) . '"/>';
        }

        return self::XML
            . '<p:presentation xmlns:a="' . self::NS_A . '" xmlns:r="' . self::NS_R . '" xmlns:p="' . self::NS_P . '">'
            . '<p:sldMasterIdLst><p:sldMasterId id="2147483648" r:id="rId1"/></p:sldMasterIdLst>'
            . '<p:sldIdLst>' . $slides . '</p:sldIdLst>'
            . '<p:sldSz cx="' . self::WIDTH . '" cy="' . self::HEIGHT . '"/><p:notesSz cx="6858000" cy="9144000"/>'
            . '</p:presentation>';
    }

    private function slideMaster(): string
    {
        return self::XML
            . '<p:sldMaster xmlns:a="' . self::NS_A . '" xmlns:r="' . self::NS_R . '" xmlns:p="' . self::NS_P . '">'
            . '<p:cSld><p:bg><p:bgRef idx="1001"><a:schemeClr val="bg1"/></p:bgRef></p:bg>'
            . '<p:spTree>' . $this->groupProperties() . '</p:spTree></p:cSld>'
            . '<p:clrMap bg1="lt1" tx1="dk1" bg2="lt2" tx2="dk2" accent1="accent1" accent2="accent2" accent3="accent3"'
            . ' accent4="accent4" accent5="accent5" accent6="accent6" hlink="hlink" folHlink="folHlink"/>'
            . '<p:sldLayoutIdLst><p:sldLayoutId id="2147483649" r:id="rId1"/></p:sldLayoutIdLst>'
            . '</p:sldMaster>';
    }

    private function slideLayout(): string
    {
        return self::XML
            . '<p:sldLayout xmlns:a="' . self::NS_A . '" xmlns:r="' . self::NS_R . '" xmlns:p="' . self::NS_P . '" type="blank" preserve="1">'
            . '<p:cSld name="Blank"><p:spTree>' . $this->groupProperties() . '</p:spTree></p:cSld>'
            . '<p:clrMapOvr><a:masterClrMapping/></p:clrMapOvr></p:sldLayout>';
    }

    /** @param array{dark: string, accent: string, light: string, text: string} $palette */
    private function theme(array $palette): string
    {
        $line = '<a:ln w="9525"><a:solidFill><a:schemeClr val="phClr"/></a:solidFill></a:ln>';
        $fill = '<a:solidFill><a:schemeClr val="phClr"/></a:solidFill>';
        $font = '<a:latin typeface="Calibri"/><a:ea typeface=""/><a:cs typeface=""/>';

        return self::XML
            . '<a:theme xmlns:a="' . self::NS_A . '" name="Apie faker"><a:themeElements>'
            . '<a:clrScheme name="Apie faker">'
            . '<a:dk1><a:srgbClr val="' . $palette['text'] . '"/></a:dk1><a:lt1><a:srgbClr val="FFFFFF"/></a:lt1>'
            . '<a:dk2><a:srgbClr val="' . $palette['dark'] . '"/></a:dk2><a:lt2><a:srgbClr val="' . $palette['light'] . '"/></a:lt2>'
            . '<a:accent1><a:srgbClr val="' . $palette['accent'] . '"/></a:accent1><a:accent2><a:srgbClr val="' . $palette['dark'] . '"/></a:accent2>'
            . '<a:accent3><a:srgbClr val="7F7F7F"/></a:accent3><a:accent4><a:srgbClr val="A5A5A5"/></a:accent4>'
            . '<a:accent5><a:srgbClr val="BFBFBF"/></a:accent5><a:accent6><a:srgbClr val="595959"/></a:accent6>'
            . '<a:hlink><a:srgbClr val="0563C1"/></a:hlink><a:folHlink><a:srgbClr val="954F72"/></a:folHlink>'
            . '</a:clrScheme>'
            . '<a:fontScheme name="Apie faker"><a:majorFont>' . $font . '</a:majorFont><a:minorFont>' . $font . '</a:minorFont></a:fontScheme>'
            . '<a:fmtScheme name="Apie faker">'
            . '<a:fillStyleLst>' . $fill . $fill . $fill . '</a:fillStyleLst>'
            . '<a:lnStyleLst>' . $line . $line . $line . '</a:lnStyleLst>'
            . '<a:effectStyleLst>' . str_repeat('<a:effectStyle><a:effectLst/></a:effectStyle>', 3) . '</a:effectStyleLst>'
            . '<a:bgFillStyleLst>' . $fill . $fill . $fill . '</a:bgFillStyleLst>'
            . '</a:fmtScheme></a:themeElements></a:theme>';
    }
}
