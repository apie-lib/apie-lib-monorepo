<?php

namespace Apie\Fixtures;

use Symfony\Component\Finder\Finder;

/**
 * @codeCoverageIgnore
 */
final class FuturePhpVersion
{
    private function __construct()
    {
    }

    public static function loadPhp84Classes(): void
    {
        if (PHP_VERSION_ID < 80400) {
            throw new \LogicException('This is not PHP version 8.4 or newer, this is PHP version ' . PHP_VERSION);
        }
        foreach (Finder::create()->in(__DIR__ . '/../php84')->files()->name('*.php') as $phpFile) {
            require_once $phpFile->getRealPath();
        }
    }
}
