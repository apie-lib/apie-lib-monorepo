<?php
namespace Apie\Fixtures\BackgroundProcess;

use Apie\Core\BackgroundProcess\BackgroundProcessDeclaration;

class SequentialExample implements BackgroundProcessDeclaration
{
    public static function retrieveDeclaration(int $version): array
    {
        if ($version === 1) {
            return [
                function (int $payload) {
                    return $payload / $payload;
                }
            ];
        }

        return [
            function (int $payload) {
                return 42;
            },
            function (int $payload) {
                return $payload / $payload;
            }
        ];
    }

    public function getCurrentVersion(): int
    {
        return 2;
    }

    public static function getMaxRetries(int $version): int
    {
        return $version;
    }
}
