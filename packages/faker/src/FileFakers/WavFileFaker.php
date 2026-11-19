<?php
namespace Apie\Faker\FileFakers;

use Apie\Faker\Interfaces\ApieAudioFileFaker;
use Faker\Generator;
use Nyholm\Psr7\Stream;

/**
 * Generates a short 16-bit mono PCM wav file: a random pentatonic tune in a random waveform.
 */
final class WavFileFaker implements ApieAudioFileFaker
{
    private const SAMPLE_RATE = 22050;
    private const PENTATONIC = [0, 2, 4, 7, 9];
    private const WAVEFORMS = ['sine', 'triangle', 'square', 'saw'];

    public function createOriginalFilename(Generator $faker): string
    {
        return $faker->word() . '.wav';
    }

    public function createMimeType(): string
    {
        return 'audio/wav';
    }

    public static function isSupported(): bool
    {
        return true;
    }

    /** @return resource */
    public function createResource(Generator $faker, string $originalFilename, string $mimeType): mixed
    {
        $pcm = $this->createSamples($faker);
        $header = 'RIFF' . pack('V', 36 + strlen($pcm)) . 'WAVE'
            . 'fmt ' . pack('VvvVVvv', 16, 1, 1, self::SAMPLE_RATE, self::SAMPLE_RATE * 2, 2, 16)
            . 'data' . pack('V', strlen($pcm));

        return Stream::create($header . $pcm)->detach();
    }

    private function createSamples(Generator $faker): string
    {
        $waveform = $faker->randomElement(self::WAVEFORMS);
        $root = $faker->numberBetween(48, 60);
        $noteLength = $faker->randomElement([0.15, 0.2, 0.3, 0.4]);
        $vibrato = $faker->boolean(40);
        $degree = $faker->numberBetween(0, 9);
        $pcm = '';
        for ($note = $faker->numberBetween(8, 24); $note > 0; $note--) {
            $degree = max(0, min(9, $degree + $faker->numberBetween(-2, 2)));
            $midi = $root + self::PENTATONIC[$degree % 5] + 12 * intdiv($degree, 5);
            $frequency = 440 * 2 ** (($midi - 69) / 12);
            $length = (int) (self::SAMPLE_RATE * $noteLength * ($faker->boolean(25) ? 2 : 1));
            $pcm .= $faker->boolean(10)
                ? str_repeat("\0\0", $length)
                : $this->renderNote($waveform, $frequency, $length, $vibrato);
        }

        return $pcm;
    }

    private function renderNote(string $waveform, float $frequency, int $length, bool $vibrato): string
    {
        $samples = [];
        $attack = min(300, intdiv($length, 4));
        $phase = 0.0;
        for ($i = 0; $i < $length; $i++) {
            $freq = $vibrato ? $frequency * (1 + 0.01 * sin(2 * M_PI * 5 * $i / self::SAMPLE_RATE)) : $frequency;
            $phase = fmod($phase + $freq / self::SAMPLE_RATE, 1.0);
            $wave = match ($waveform) {
                'sine' => sin(2 * M_PI * $phase),
                'triangle' => 4 * abs($phase - 0.5) - 1,
                'square' => $phase < 0.5 ? 0.6 : -0.6,
                default => 2 * $phase - 1,
            };
            // linear attack and exponential decay avoid clicks between notes
            $envelope = min(1.0, $i / max(1, $attack)) * exp(-3 * $i / $length) * min(1.0, ($length - $i) / 200);
            $samples[] = (int) round($wave * $envelope * 0.5 * 32767);
        }

        return pack('v*', ...array_map(static fn (int $s): int => $s & 0xFFFF, $samples));
    }
}
