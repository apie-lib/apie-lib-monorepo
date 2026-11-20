<?php
namespace Apie\Faker\FileFakers;

use Apie\Faker\Interfaces\ApieImageFileFaker;
use Faker\Generator;
use Nyholm\Psr7\Stream;

/**
 * Generates a fictional company logo from a random arrangement of geometric shapes.
 */
final class SvgCompanyLogoFileFaker implements ApieImageFileFaker
{
    private const COLORS = ['#264653', '#2A9D8F', '#E9C46A', '#F4A261', '#E76F51', '#3A506B', '#5BC0BE', '#9B5DE5', '#F15BB5', '#00BBF9', '#00F5D4', '#FF6B6B'];

    public function createOriginalFilename(Generator $faker): string
    {
        return $faker->word() . '.svg';
    }

    public function createMimeType(): string
    {
        return 'image/svg+xml';
    }

    public static function isSupported(): bool
    {
        return true;
    }

    /** @return resource */
    public function createResource(Generator $faker, string $originalFilename, string $mimeType): mixed
    {
        return Stream::create($this->createSvg($faker))->detach();
    }

    private function createSvg(Generator $faker): string
    {
        $colors = $faker->randomElements(self::COLORS, 3);
        $elements = [];
        $count = $faker->numberBetween(3, 5);
        for ($i = 0; $i < $count; $i++) {
            $angle = 360 * $i / $count + $faker->numberBetween(-12, 12);
            $distance = $faker->numberBetween(28, 38);
            $x = 128 + (int) round(cos(deg2rad($angle)) * $distance);
            $y = 128 + (int) round(sin(deg2rad($angle)) * $distance);
            $size = $faker->numberBetween(13, 23);
            $color = $faker->randomElement($colors);
            $shape = $faker->randomElement(['circle', 'diamond', 'square', 'triangle']);

            $elements[] = '<g transform="translate(' . $x . ' ' . $y . ') rotate(' . $faker->numberBetween(0, 359) . ')">'
                . $this->shape($shape, $size, $color)
                . '</g>';
        }

        $centerColor = $faker->randomElement($colors);
        $centerShape = $faker->randomElement(['circle', 'diamond', 'square']);
        $elements[] = '<g transform="translate(128 128) rotate(' . $faker->numberBetween(0, 359) . ')">'
            . $this->shape($centerShape, $faker->numberBetween(12, 20), $centerColor)
            . '</g>';

        return '<?xml version="1.0" encoding="UTF-8"?>'
            . '<svg xmlns="http://www.w3.org/2000/svg" width="256" height="256" viewBox="0 0 256 256">'
            . implode('', $elements)
            . '</svg>';
    }

    private function shape(string $shape, int $size, string $color): string
    {
        return match ($shape) {
            'diamond' => '<path d="M0 ' . -$size . ' L' . $size . ' 0 L0 ' . $size . ' L' . -$size . ' 0 Z" fill="' . $color . '"/>',
            'square' => '<rect x="' . -$size . '" y="' . -$size . '" width="' . ($size * 2) . '" height="' . ($size * 2) . '" rx="' . (int) round($size / 3) . '" fill="' . $color . '"/>',
            'triangle' => '<path d="M0 ' . -$size . ' L' . $size . ' ' . $size . ' L' . -$size . ' ' . $size . ' Z" fill="' . $color . '"/>',
            default => '<circle r="' . $size . '" fill="' . $color . '"/>',
        };
    }
}
