<?php
namespace Apie\Faker\FileFakers;

use Apie\Faker\Interfaces\ApieAudioFileFaker;
use Apie\Faker\Support\Mp3Notes;
use Faker\Generator;
use Nyholm\Psr7\Stream;

/**
 * Generates an mp3 file with random ID3v2.3 metadata and a random pentatonic tune.
 * The tune is made by joining pre-encoded note frames, so no mp3 encoder is required.
 */
final class Mp3FileFaker implements ApieAudioFileFaker
{
    private const GENRES = ['Pop', 'Rock', 'Jazz', 'Electronic', 'Ambient', 'Classical', 'Folk', 'Hip-Hop', 'Blues', 'Soundtrack'];

    public function createOriginalFilename(Generator $faker): string
    {
        return $faker->word() . '.mp3';
    }

    public function createMimeType(): string
    {
        return 'audio/mpeg';
    }

    public static function isSupported(): bool
    {
        return true;
    }

    /** @return resource */
    public function createResource(Generator $faker, string $originalFilename, string $mimeType): mixed
    {
        $notes = Mp3Notes::decode();
        $frames = '';
        $current = $faker->numberBetween(0, count($notes) - 1);
        for ($i = $faker->numberBetween(12, 48); $i > 0; $i--) {
            // mostly stepwise movement with an occasional repeat or leap makes it sound like a melody.
            $current = max(0, min(count($notes) - 1, $current + $faker->randomElement([-2, -1, -1, 0, 1, 1, 2])));
            $frames .= $notes[$current];
        }

        return Stream::create($this->createId3Tag($faker) . $frames)->detach();
    }

    private function createId3Tag(Generator $faker): string
    {
        $trackCount = $faker->numberBetween(1, 16);
        $frames = $this->textFrame('TIT2', ucfirst($faker->words(3, true)))
            . $this->textFrame('TPE1', $faker->name())
            . $this->textFrame('TALB', ucwords($faker->words(2, true)))
            . $this->textFrame('TPE2', $faker->name())
            . $this->textFrame('TYER', (string) $faker->year())
            . $this->textFrame('TRCK', $faker->numberBetween(1, $trackCount) . '/' . $trackCount)
            . $this->textFrame('TCON', $faker->randomElement(self::GENRES))
            . $this->textFrame('TBPM', (string) $faker->numberBetween(60, 180))
            . $this->textFrame('TCOP', $faker->year() . ' ' . $faker->company())
            . $this->commentFrame($faker->sentence());

        return 'ID3' . "\x03\x00\x00" . $this->syncSafe(strlen($frames)) . $frames;
    }

    private function textFrame(string $id, string $text): string
    {
        // encoding 1 is UTF-16 with a byte order mark.
        return $this->frame($id, "\x01" . $this->utf16($text));
    }

    private function commentFrame(string $text): string
    {
        return $this->frame('COMM', "\x01" . 'eng' . "\xFF\xFE\x00\x00" . $this->utf16($text));
    }

    private function frame(string $id, string $body): string
    {
        return $id . pack('N', strlen($body)) . "\x00\x00" . $body;
    }

    private function utf16(string $text): string
    {
        return "\xFF\xFE" . mb_convert_encoding($text, 'UTF-16LE', 'UTF-8') . "\x00\x00";
    }

    private function syncSafe(int $size): string
    {
        return chr(($size >> 21) & 0x7F) . chr(($size >> 14) & 0x7F) . chr(($size >> 7) & 0x7F) . chr($size & 0x7F);
    }
}
