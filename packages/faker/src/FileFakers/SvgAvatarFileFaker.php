<?php
namespace Apie\Faker\FileFakers;

use Apie\Faker\Interfaces\ApieImageFileFaker;
use Faker\Generator;
use Nyholm\Psr7\Stream;

/**
 * Generates a cartoony avatar as svg: a random combination of skin, hair, eyes, mouth, glasses, beard and clothes.
 */
final class SvgAvatarFileFaker implements ApieImageFileFaker
{
    private const BACKGROUNDS = ['#FFD6A5', '#CAFFBF', '#9BF6FF', '#A0C4FF', '#BDB2FF', '#FFC6FF', '#FDFFB6', '#FFADAD', '#B8E0D2', '#E4C1F9'];
    private const SKIN_TONES = ['#FFDBB4', '#F1C27D', '#E0AC69', '#C68642', '#8D5524', '#6B4226', '#FAD7C0'];
    private const HAIR_COLORS = ['#1B1B1B', '#4A312C', '#8B5A2B', '#D6A64F', '#B55239', '#E8E1CC', '#3B5BDB', '#C2255C', '#2F9E44'];
    private const CLOTHES = ['#E63946', '#457B9D', '#2A9D8F', '#F4A261', '#6D597A', '#264653', '#F72585', '#4361EE', '#FFFFFF'];
    private const HAIR_STYLES = ['bald', 'short', 'long', 'bun', 'curly', 'mohawk'];
    private const EYE_STYLES = ['dot', 'big', 'happy', 'sleepy', 'wink'];
    private const MOUTH_STYLES = ['smile', 'open', 'flat', 'smirk', 'tongue'];

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
        $skin = $faker->randomElement(self::SKIN_TONES);
        $hairColor = $faker->randomElement(self::HAIR_COLORS);
        $hairStyle = $faker->randomElement(self::HAIR_STYLES);
        $clothes = $faker->randomElement(self::CLOTHES);
        $background = $faker->randomElement(self::BACKGROUNDS);
        $clip = $faker->boolean(70)
            ? '<circle cx="128" cy="128" r="128"/>'
            : '<rect width="256" height="256" rx="' . $faker->numberBetween(0, 64) . '"/>';

        $svg = '<?xml version="1.0" encoding="UTF-8"?>'
            . '<svg xmlns="http://www.w3.org/2000/svg" width="256" height="256" viewBox="0 0 256 256">'
            . '<defs><clipPath id="avatar">' . $clip . '</clipPath></defs>'
            . '<g clip-path="url(#avatar)">'
            . '<rect width="256" height="256" fill="' . $background . '"/>';
        $svg .= $this->hairBack($hairStyle, $hairColor);
        $svg .= $this->body($faker, $skin, $clothes);
        $svg .= '<ellipse cx="72" cy="124" rx="9" ry="14" fill="' . $skin . '"/>'
            . '<ellipse cx="184" cy="124" rx="9" ry="14" fill="' . $skin . '"/>'
            . '<ellipse cx="128" cy="118" rx="52" ry="58" fill="' . $skin . '"/>';
        if ($faker->boolean(25)) {
            $svg .= '<path d="M82 132 C 90 190 166 190 174 132 C 166 154 148 160 128 160 C 108 160 90 154 82 132 Z" fill="' . $hairColor . '"/>';
        }
        $svg .= $this->hairFront($hairStyle, $hairColor);
        $svg .= $this->eyebrows($faker, $hairColor);
        $svg .= $this->eyes($faker->randomElement(self::EYE_STYLES));
        // nose
        $svg .= '<path d="M124 128 Q 120 142 128 143 Q 134 143 132 138" fill="none" stroke="#00000040" stroke-width="3" stroke-linecap="round"/>';
        if ($faker->boolean(40)) {
            $svg .= '<circle cx="94" cy="142" r="9" fill="#FF6B6B" opacity="0.3"/><circle cx="162" cy="142" r="9" fill="#FF6B6B" opacity="0.3"/>';
        }
        $svg .= $this->mouth($faker->randomElement(self::MOUTH_STYLES));
        if ($faker->boolean(35)) {
            $svg .= $this->glasses($faker->randomElement(self::CLOTHES));
        }

        return $svg . '</g></svg>';
    }

    private function hairBack(string $style, string $color): string
    {
        return match ($style) {
            'long' => '<path d="M68 118 C 54 30 202 30 188 118 L 200 216 L 56 216 Z" fill="' . $color . '"/>',
            'bun' => '<circle cx="128" cy="34" r="24" fill="' . $color . '"/>',
            default => '',
        };
    }

    private function body(Generator $faker, string $skin, string $clothes): string
    {
        $svg = '<rect x="108" y="152" width="40" height="44" fill="' . $skin . '"/>'
            . '<path d="M30 256 C 30 204 78 184 128 184 C 178 184 226 204 226 256 Z" fill="' . $clothes . '"/>';
        // collar: either a v-neck or a round neckline
        $svg .= $faker->boolean()
            ? '<path d="M104 186 L 128 218 L 152 186 Z" fill="' . $skin . '"/>'
            : '<path d="M104 186 Q 128 212 152 186 Z" fill="' . $skin . '"/>';

        return $svg;
    }

    private function hairFront(string $style, string $color): string
    {
        $cap = '<path d="M72 112 C 66 50 108 34 128 34 C 150 34 192 50 184 112 C 176 84 154 76 128 76 C 102 76 80 84 72 112 Z" fill="' . $color . '"/>';
        switch ($style) {
            case 'short':
            case 'long':
            case 'bun':
                return $cap;
            case 'mohawk':
                return '<path d="M104 70 C 100 30 112 10 128 6 C 144 10 156 30 152 70 Z" fill="' . $color . '"/>';
            case 'curly':
                $svg = '';
                foreach ([[84, 70], [102, 54], [128, 48], [154, 54], [172, 70], [78, 94], [178, 94]] as [$x, $y]) {
                    $svg .= '<circle cx="' . $x . '" cy="' . $y . '" r="20" fill="' . $color . '"/>';
                }
                return $svg;
            default:
                return '';
        }
    }

    private function eyebrows(Generator $faker, string $color): string
    {
        $tilt = $faker->numberBetween(-6, 6);
        return '<path d="M94 ' . (100 + $tilt) . ' L 120 ' . (100 - $tilt) . '" stroke="' . $color . '" stroke-width="5" stroke-linecap="round"/>'
            . '<path d="M136 ' . (100 - $tilt) . ' L 162 ' . (100 + $tilt) . '" stroke="' . $color . '" stroke-width="5" stroke-linecap="round"/>';
    }

    private function eyes(string $style): string
    {
        $dark = '#222222';
        $arc = fn (int $x): string => '<path d="M' . ($x - 11) . ' 120 Q ' . $x . ' 106 ' . ($x + 11) . ' 120" fill="none" stroke="' . $dark . '" stroke-width="4" stroke-linecap="round"/>';
        $line = fn (int $x): string => '<path d="M' . ($x - 10) . ' 118 L ' . ($x + 10) . ' 118" stroke="' . $dark . '" stroke-width="4" stroke-linecap="round"/>';
        $dot = fn (int $x): string => '<circle cx="' . $x . '" cy="116" r="6" fill="' . $dark . '"/><circle cx="' . ($x + 2) . '" cy="114" r="2" fill="#FFFFFF"/>';
        $big = fn (int $x): string => '<ellipse cx="' . $x . '" cy="116" rx="13" ry="15" fill="#FFFFFF" stroke="' . $dark . '" stroke-width="2"/>'
            . '<circle cx="' . $x . '" cy="118" r="7" fill="' . $dark . '"/><circle cx="' . ($x + 2) . '" cy="115" r="2.5" fill="#FFFFFF"/>';

        return match ($style) {
            'big' => $big(106) . $big(150),
            'happy' => $arc(106) . $arc(150),
            'sleepy' => $line(106) . $line(150),
            'wink' => $dot(106) . $arc(150),
            default => $dot(106) . $dot(150),
        };
    }

    private function mouth(string $style): string
    {
        return match ($style) {
            'open' => '<path d="M108 152 Q 128 186 148 152 Z" fill="#7A1F2B" stroke="#222222" stroke-width="3" stroke-linejoin="round"/>',
            'flat' => '<path d="M112 158 L 144 158" stroke="#222222" stroke-width="4" stroke-linecap="round"/>',
            'smirk' => '<path d="M110 158 Q 130 162 148 148" fill="none" stroke="#222222" stroke-width="4" stroke-linecap="round"/>',
            'tongue' => '<path d="M108 152 Q 128 176 148 152 Z" fill="#7A1F2B" stroke="#222222" stroke-width="3" stroke-linejoin="round"/>'
                . '<path d="M120 164 Q 128 182 138 164 Z" fill="#FF7B9C"/>',
            default => '<path d="M108 150 Q 128 172 148 150" fill="none" stroke="#222222" stroke-width="4" stroke-linecap="round"/>',
        };
    }

    private function glasses(string $color): string
    {
        $stroke = $color === '#FFFFFF' ? '#222222' : $color;
        return '<g fill="#FFFFFF" fill-opacity="0.25" stroke="' . $stroke . '" stroke-width="4">'
            . '<circle cx="106" cy="118" r="19"/><circle cx="150" cy="118" r="19"/></g>'
            . '<path d="M125 116 L 131 116" stroke="' . $stroke . '" stroke-width="4"/>';
    }
}
