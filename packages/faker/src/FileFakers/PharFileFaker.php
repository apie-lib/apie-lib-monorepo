<?php
namespace Apie\Faker\FileFakers;

use Apie\Faker\Interfaces\ApieFileFaker;
use Apie\Faker\SeededFile;
use Faker\Generator;
use Nyholm\Psr7\Stream;

/**
 * Generates a phar archive with some small php classes and a few random data files.
 * The phar format is written by hand as the Phar class can not write when phar.readonly is enabled (default).
 */
final class PharFileFaker implements ApieFileFaker
{
    private const API_VERSION = 0x1100;
    private const FLAG_SIGNATURE = 0x00010000;
    private const SIGNATURE_SHA256 = 0x0003;

    private static bool $alreadyCreating = false;

    public function createOriginalFilename(Generator $faker): string
    {
        return $faker->word() . '.phar';
    }

    public function createMimeType(): string
    {
        return 'application/x-phar';
    }

    public static function isSupported(): bool
    {
        return !self::$alreadyCreating;
    }

    /** @return resource */
    public function createResource(Generator $faker, string $originalFilename, string $mimeType): mixed
    {
        $files = $this->createSourceFiles($faker);
        self::$alreadyCreating = true;
        try {
            for ($i = $faker->numberBetween(0, 3); $i > 0; $i--) {
                /** @var SeededFile $file */
                $file = $faker->fakeClass(SeededFile::class);
                $files['data/' . $file->getClientFilename()] = (string) $file->getStream();
            }
        } finally {
            self::$alreadyCreating = false;
        }

        return Stream::create($this->createPhar($faker, $originalFilename, $files))->detach();
    }

    /** @return array<string, string> */
    private function createSourceFiles(Generator $faker): array
    {
        $namespace = 'Faker\\' . ucfirst($faker->word()) . 'App';
        $classes = [];
        for ($i = $faker->numberBetween(1, 3); $i > 0; $i--) {
            $classes[ucfirst($faker->word()) . 'Service'] = $faker->sentence();
        }
        $files = [];
        foreach ($classes as $class => $greeting) {
            $files['src/' . $class . '.php'] = "<?php\nnamespace " . $namespace . ";\n\nclass " . $class . "\n{\n"
                . "    public function greet(): string\n    {\n        return " . var_export($greeting, true) . ";\n    }\n}\n";
        }
        $index = "<?php\n";
        foreach (array_keys($classes) as $class) {
            $index .= "require_once __DIR__ . '/src/" . $class . ".php';\n";
        }
        $index .= "\n";
        foreach (array_keys($classes) as $class) {
            $index .= 'echo (new \\' . $namespace . '\\' . $class . "())->greet(), PHP_EOL;\n";
        }
        $files['index.php'] = $index;
        ksort($files);

        return $files;
    }

    /** @param array<string, string> $files */
    private function createPhar(Generator $faker, string $alias, array $files): string
    {
        $stub = "<?php\nPhar::mapPhar(" . var_export($alias, true) . ");\n"
            . 'require ' . var_export('phar://' . $alias . '/index.php', true) . ";\n"
            . "__HALT_COMPILER(); ?>\r\n";
        $manifest = pack('V', count($files)) . pack('n', self::API_VERSION) . pack('V', self::FLAG_SIGNATURE)
            . pack('V', strlen($alias)) . $alias . pack('V', 0);
        $contents = '';
        foreach ($files as $name => $data) {
            $manifest .= pack('V', strlen($name)) . $name
                . pack('V', strlen($data))
                . pack('V', $faker->dateTimeBetween('-2 years')->getTimestamp())
                . pack('V', strlen($data))
                . pack('V', crc32($data))
                . pack('V', 0644)
                . pack('V', 0);
            $contents .= $data;
        }
        $phar = $stub . pack('V', strlen($manifest)) . $manifest . $contents;

        return $phar . hash('sha256', $phar, true) . pack('V', self::SIGNATURE_SHA256) . 'GBMB';
    }
}
