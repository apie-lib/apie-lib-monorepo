<?php
namespace App\ApiePlayground\Example\BackgroundProcess;

use Apie\Core\BackgroundProcess\BackgroundProcessDeclaration;

class ExpensiveCalculation implements BackgroundProcessDeclaration
{
    public static function retrieveDeclaration(int $version): array
    {
        return [
            function (int $number) {
                return $number * $number;
            }
        ];
    }

    public function getCurrentVersion(): int
    {
        return 1;
    }

    public function getMaxRetries(int $version): int
    {
        return 1;
    }
}