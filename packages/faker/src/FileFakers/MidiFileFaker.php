<?php
namespace Apie\Faker\FileFakers;

use Apie\Faker\Interfaces\ApieAudioFileFaker;
use Faker\Generator;
use Nyholm\Psr7\Stream;

/**
 * Generates a small song as standard midi file (format 1) with 3 tracks: melody, chords and drums.
 *
 * Song structure: intro, chorus, verse, chorus, verse, outro. A bridge is inserted once at a random position.
 */
final class MidiFileFaker implements ApieAudioFileFaker
{
    private const TICKS_PER_QUARTER = 480;
    private const BAR_TICKS = self::TICKS_PER_QUARTER * 4;
    private const EIGHTH = self::TICKS_PER_QUARTER / 2;

    private const MAJOR = [0, 2, 4, 5, 7, 9, 11];
    private const MINOR = [0, 2, 3, 5, 7, 8, 10];

    private const PROGRESSIONS = [
        [0, 4, 5, 3],
        [0, 3, 4, 3],
        [0, 5, 3, 4],
        [5, 3, 0, 4],
        [0, 2, 3, 4],
        [1, 4, 0, 5],
    ];

    private const KICK = 36;
    private const SNARE = 38;
    private const HIHAT = 42;
    private const OPEN_HIHAT = 46;
    private const CRASH = 49;

    public function createOriginalFilename(Generator $faker): string
    {
        return $faker->word() . '.mid';
    }

    public function createMimeType(): string
    {
        return 'audio/midi';
    }

    public static function isSupported(): bool
    {
        return true;
    }

    /** @return resource */
    public function createResource(Generator $faker, string $originalFilename, string $mimeType): mixed
    {
        return Stream::create($this->createMidi($faker))->detach();
    }

    private function createMidi(Generator $faker): string
    {
        $scale = $faker->boolean() ? self::MAJOR : self::MINOR;
        $root = $faker->numberBetween(0, 11);
        $tempo = $faker->numberBetween(80, 150);

        $progressions = [
            'chorus' => $faker->randomElement(self::PROGRESSIONS),
            'verse' => $faker->randomElement(self::PROGRESSIONS),
            'bridge' => $faker->randomElement(self::PROGRESSIONS),
        ];
        $progressions['intro'] = $progressions['verse'];
        $progressions['outro'] = $progressions['chorus'];

        $sections = [
            ['intro', 2],
            ['chorus', 4],
            ['verse', 4],
            ['chorus', 4],
            ['verse', 4],
            ['outro', 2],
        ];
        // bridge goes after either verse, so it always leads into a chorus or the outro.
        array_splice($sections, $faker->randomElement([3, 5]), 0, [['bridge', 4]]);

        $melodies = [];
        $melody = [];
        $chords = [];
        $drums = [];
        $tick = 0;
        foreach ($sections as [$type, $bars]) {
            $progression = $progressions[$type];
            $melodyKey = in_array($type, ['intro', 'outro'], true) ? ($type === 'intro' ? 'verse' : 'chorus') : $type;
            $melodies[$melodyKey] ??= $this->createMelodyBars($faker, $progressions[$melodyKey], $melodyKey);
            for ($bar = 0; $bar < $bars; $bar++) {
                $chordDegree = $progression[$bar % 4];
                $barStart = $tick + $bar * self::BAR_TICKS;
                $isLastBar = $bar === $bars - 1;
                if ($type !== 'intro' || $bar > 0) {
                    foreach ($melodies[$melodyKey][$bar % 4] as [$slot, $length, $offset]) {
                        $degree = $chordDegree + $offset;
                        $this->addNote(
                            $melody,
                            $barStart + $slot * self::EIGHTH,
                            $length * self::EIGHTH,
                            $this->toNote($scale, $root, $degree, 72),
                            $type === 'chorus' ? 100 : 85,
                            0
                        );
                    }
                }
                $this->addChord($chords, $barStart, $scale, $root, $chordDegree, $type);
                $this->addDrums($drums, $barStart, $type, $bar === 0, $isLastBar);
            }
            $tick += $bars * self::BAR_TICKS;
        }

        $tempoTrack = [
            [0, 0, "\xFF\x51\x03" . substr(pack('N', intdiv(60_000_000, $tempo)), 1)],
            [0, 0, "\xFF\x58\x04\x04\x02\x18\x08"],
        ];
        // Program changes: piano for the chords, synth lead for the melody.
        $melody[] = [0, 0, "\xC0" . chr(80)];
        $chords[] = [0, 0, "\xC1" . chr(0)];

        $header = 'MThd' . pack('Nnnn', 6, 1, 3, self::TICKS_PER_QUARTER);

        return $header
            . $this->encodeTrack(array_merge($tempoTrack, $melody), $tick)
            . $this->encodeTrack($chords, $tick)
            . $this->encodeTrack($drums, $tick);
    }

    /**
     * @param array<int, int> $progression
     * @return array<int, array<int, array{int, int, int}>> per bar a list of [eighth slot, length in eighths, degree offset]
     */
    private function createMelodyBars(Generator $faker, array $progression, string $type): array
    {
        $bars = [];
        $offset = 0;
        $maxOffset = $type === 'chorus' ? 9 : 6;
        foreach ($progression as $barIndex => $_) {
            $notes = [];
            $slot = 0;
            while ($slot < 8) {
                $length = min(8 - $slot, $faker->randomElement($type === 'chorus' ? [1, 2, 2, 4] : [2, 2, 4, 4]));
                if ($slot > 0 && $faker->boolean(15)) {
                    $slot += $length;
                    continue;
                }
                // first note of a bar is a chord tone (0, 2 or 4 steps from the chord root), others move stepwise.
                $offset = $slot === 0
                    ? $faker->randomElement([0, 2, 4])
                    : max(-2, min($maxOffset, $offset + $faker->randomElement([-2, -1, -1, 1, 1, 2])));
                $notes[] = [$slot, $length, $offset];
                $slot += $length;
            }
            // resolve to the root on the very last bar.
            if ($barIndex === 3) {
                $last = array_pop($notes);
                $notes[] = [$last[0], $last[1], 0];
            }
            $bars[] = $notes;
        }

        return $bars;
    }

    /** @param array<int, int> $scale */
    private function toNote(array $scale, int $root, int $degree, int $base): int
    {
        $octave = intdiv($degree, 7) - ($degree < 0 && $degree % 7 !== 0 ? 1 : 0);
        $index = (($degree % 7) + 7) % 7;
        return $base - 12 + $root + $scale[$index] + 12 * $octave;
    }

    /**
     * @param array<int, array{int, int, string}> $events
     * @param array<int, int> $scale
     */
    private function addChord(array &$events, int $barStart, array $scale, int $root, int $degree, string $type): void
    {
        $notes = [];
        foreach ([0, 2, 4] as $step) {
            $notes[] = $this->toNote($scale, $root, $degree + $step, 60);
        }
        $velocity = $type === 'chorus' ? 80 : 65;
        if ($type === 'chorus') {
            // chorus: repeated half-bar stabs
            foreach ([0, self::BAR_TICKS / 2] as $offset) {
                foreach ($notes as $note) {
                    $this->addNote($events, $barStart + $offset, self::BAR_TICKS / 2 - 10, $note, $velocity, 1);
                }
            }
            return;
        }
        foreach ($notes as $note) {
            $this->addNote($events, $barStart, self::BAR_TICKS - 10, $note, $velocity, 1);
        }
    }

    /** @param array<int, array{int, int, string}> $events */
    private function addDrums(array &$events, int $barStart, string $type, bool $firstBar, bool $lastBar): void
    {
        $hit = function (int $slot, int $note, int $velocity) use (&$events, $barStart): void {
            $this->addNote($events, $barStart + $slot * self::EIGHTH, self::EIGHTH - 10, $note, $velocity, 9);
        };
        if ($firstBar && $type !== 'intro') {
            $hit(0, self::CRASH, 110);
        }
        // hi-hat
        for ($slot = 0; $slot < 8; $slot++) {
            if ($type === 'intro' || $type === 'outro' || $type === 'verse' || $type === 'chorus' || $slot % 2 === 0) {
                $hit($slot, $type === 'chorus' && $slot === 7 ? self::OPEN_HIHAT : self::HIHAT, $slot % 2 === 0 ? 80 : 60);
            }
        }
        if ($type === 'intro') {
            return;
        }
        if ($type === 'bridge') {
            // half-time feel
            $hit(0, self::KICK, 100);
            $hit(4, self::SNARE, 100);
        } else {
            $hit(0, self::KICK, 110);
            $hit(4, self::KICK, 100);
            $hit(2, self::SNARE, 100);
            $hit(6, self::SNARE, 100);
            if ($type === 'chorus') {
                $hit(5, self::KICK, 90);
            }
        }
        // small fill on the last bar before the next section
        if ($lastBar && $type !== 'outro') {
            $hit(7, self::SNARE, 110);
        }
    }

    /** @param array<int, array{int, int, string}> $events */
    private function addNote(array &$events, int $start, int $length, int $note, int $velocity, int $channel): void
    {
        $note = max(0, min(127, $note));
        // order 1 for note off so off events at the same tick are sorted before note on events (order 2).
        $events[] = [$start, 2, chr(0x90 | $channel) . chr($note) . chr($velocity)];
        $events[] = [$start + $length, 1, chr(0x80 | $channel) . chr($note) . chr(0)];
    }

    /** @param array<int, array{int, int, string}> $events */
    private function encodeTrack(array $events, int $endTick): string
    {
        usort($events, fn (array $a, array $b) => [$a[0], $a[1]] <=> [$b[0], $b[1]]);
        $data = '';
        $previous = 0;
        foreach ($events as [$tick, , $bytes]) {
            $data .= $this->variableLength($tick - $previous) . $bytes;
            $previous = $tick;
        }
        $data .= $this->variableLength(max(0, $endTick - $previous)) . "\xFF\x2F\x00";

        return 'MTrk' . pack('N', strlen($data)) . $data;
    }

    private function variableLength(int $value): string
    {
        $result = chr($value & 0x7F);
        while ($value >>= 7) {
            $result = chr(($value & 0x7F) | 0x80) . $result;
        }
        return $result;
    }
}
