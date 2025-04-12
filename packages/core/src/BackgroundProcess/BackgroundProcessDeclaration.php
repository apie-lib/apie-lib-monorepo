<?php
namespace Apie\Core\BackgroundProcess;

interface BackgroundProcessDeclaration
{
    public static function retrieveDeclaration(int $version): array;

    public function getCurrentVersion(): int;

    public static function getMaxRetries(int $version): int;
}
