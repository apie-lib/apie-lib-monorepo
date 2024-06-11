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

    public function getPackageFolder(): string
    {
        return $this->packageFolder;
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
                '{badges}',
            ],
            [
                $this->package,
                $this->description,
                $this->docs,
                $this->namespace,
                ComposerTools::renderBadges($this->package),
            ],
            $source
        );
        @mkdir(dirname($target), 0755, true);
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
        `vendor/bin/monorepo-builder merge -vvv && composer update`;
    }

    public static function renderBadges(string $package, ?string $repoName = null)
    {
        $repoName ??= $package;
        $donateBadge = '';
        $coverageBadge = "[![PHP Composer](https://apie-lib.github.io/projectCoverage/coverage-$package.svg)](https://apie-lib.github.io/projectCoverage/app/packages/$package/index.html)";
        if ($repoName === 'apie-lib-monorepo') {
            $donateBadge = '[![Donate](https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif)](https://www.paypal.com/donate/?hosted_button_id=J4CAFUAW7VTAY)';
            $coverageBadge = '[![Code coverage](https://apie-lib.github.io/projectCoverage/coverage_badge.svg)](https://apie-lib.github.io/projectCoverage/)';
        }
        return str_replace(PHP_EOL, ' ', "
[![Latest Stable Version](http://poser.pugx.org/apie/$package/v)](https://packagist.org/packages/apie/$package)
[![Total Downloads](http://poser.pugx.org/apie/$package/downloads)](https://packagist.org/packages/apie/$package)
[![Latest Unstable Version](http://poser.pugx.org/apie/$package/v/unstable)](https://packagist.org/packages/apie/$package)
[![License](http://poser.pugx.org/apie/$package/license)](https://packagist.org/packages/apie/$package)
$coverageBadge
$donateBadge
");
    }
}