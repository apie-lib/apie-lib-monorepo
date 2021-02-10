<?php
class Package {
    /**
     * @var string
     */
    private $packageFolder;
    /**
     * @var string
     */
    private $package;
    /**
     * @var string
     */
    private $description;
    /**
     * @var string
     */
    private $namespace;
    /**
     * @var string
     */
    private $docs;

    public function __construct(string $packageFolder)
    {
        $this->packageFolder = $packageFolder;
        $this->package = basename($packageFolder);
        $this->description = 'Composer package of the apie library: '
            . implode(
                ' ',
                array_map(
                    'lcfirst',
                    explode('-', $this->package)
                )
            );
        $this->namespace = str_replace('-', '', ucwords($this->package, '-'));
        $this->docs = 'This package is used internally in Apie or no documentation is available right now';
        $contents = @file_get_contents($packageFolder . '/README.md');
        if (false !== $contents) {
            if (preg_match('/## Documentation\n(?<docs>(.|\n)*)$/m', $contents, $match)) {
                $this->docs = trim($match['docs']);
            }
        }
        $contents = @file_get_contents($packageFolder . '/composer.json');
        if (false !== $contents) {
            $json = json_decode($contents, true);
            if (isset($json['description'])) {
                $this->description = $json['description'];
            }
        }
    }

    public function createFile(string $fileName, bool $overwrite)
    {
        $source = file_get_contents(__DIR__ . '/resources/template/' . $fileName);
        $target = $this->packageFolder . '/' . $fileName;
        if (!$overwrite && file_exists($target)) {
            return;
        }
        $source = str_replace(
            [
                '{package}',
                '{description}',
                '{docs}',
                '{namespace}',
            ],
            [
                $this->package,
                $this->description,
                $this->docs,
                $this->namespace
            ],
            $source
        );
        file_put_contents($target, $source);
    }

    public function getTestFolder(): string
    {
        return 'packages/' . $this->package . '/tests';
    }

    /**
     * @return string
     */
    public function getPackage(): string
    {
        return $this->package;
    }
}

class ComposerTools {
    public static function runUpdate()
    {
        `vendor/bin/monorepo-builder merge && composer update`;
    }
}