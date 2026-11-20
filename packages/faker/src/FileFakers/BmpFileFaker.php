<?php
namespace Apie\Faker\FileFakers;

use Apie\Faker\Interfaces\ApieImageFileFaker;
use Faker\Generator;
use Nyholm\Psr7\Stream;

/**
 * Generates an uncompressed 24-bit bmp: a four corner gradient with random circles and optional diagonal stripes.
 */
final class BmpFileFaker implements ApieImageFileFaker
{
    public function createOriginalFilename(Generator $faker): string
    {
        return $faker->word() . '.bmp';
    }

    public function createMimeType(): string
    {
        return 'image/bmp';
    }

    public static function isSupported(): bool
    {
        return true;
    }

    /** @return resource */
    public function createResource(Generator $faker, string $originalFilename, string $mimeType): mixed
    {
        $width = $faker->numberBetween(96, 256);
        $height = $faker->numberBetween(96, 256);
        $corners = [];
        for ($i = 0; $i < 4; $i++) {
            $corners[] = $this->randomColor($faker);
        }
        $circles = [];
        for ($i = $faker->numberBetween(2, 6); $i > 0; $i--) {
            $circles[] = [
                $faker->numberBetween(0, $width),
                $faker->numberBetween(0, $height),
                $faker->numberBetween(10, intdiv(min($width, $height), 2)),
                $this->randomColor($faker),
            ];
        }
        $stripes = $faker->boolean(40);
        $padding = str_repeat("\0", (4 - ($width * 3) % 4) % 4);

        $pixels = '';
        // bmp stores the rows from bottom to top.
        for ($y = $height - 1; $y >= 0; $y--) {
            $v = $y / ($height - 1);
            for ($x = 0; $x < $width; $x++) {
                $u = $x / ($width - 1);
                $color = [0, 0, 0];
                foreach ([0, 1, 2] as $channel) {
                    $top = $corners[0][$channel] * (1 - $u) + $corners[1][$channel] * $u;
                    $bottom = $corners[2][$channel] * (1 - $u) + $corners[3][$channel] * $u;
                    $color[$channel] = $top * (1 - $v) + $bottom * $v;
                }
                foreach ($circles as [$cx, $cy, $radius, $circleColor]) {
                    if (($x - $cx) ** 2 + ($y - $cy) ** 2 <= $radius ** 2) {
                        $color = $circleColor;
                    }
                }
                if ($stripes && (($x + $y) >> 4) & 1) {
                    $color = [$color[0] * 0.85, $color[1] * 0.85, $color[2] * 0.85];
                }
                $pixels .= chr((int) $color[2]) . chr((int) $color[1]) . chr((int) $color[0]);
            }
            $pixels .= $padding;
        }

        $header = 'BM' . pack('VvvV', 54 + strlen($pixels), 0, 0, 54)
            . pack('VVVvvVVVVVV', 40, $width, $height, 1, 24, 0, strlen($pixels), 2835, 2835, 0, 0);

        return Stream::create($header . $pixels)->detach();
    }

    /** @return array{int, int, int} */
    private function randomColor(Generator $faker): array
    {
        return [$faker->numberBetween(0, 255), $faker->numberBetween(0, 255), $faker->numberBetween(0, 255)];
    }
}
