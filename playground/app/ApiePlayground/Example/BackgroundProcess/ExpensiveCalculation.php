<?php
namespace App\ApiePlayground\Example\BackgroundProcess;

use Apie\Core\BackgroundProcess\BackgroundProcessDeclaration;
use Apie\Core\Context\ApieContext;
use Apie\Core\ContextConstants;
use Apie\Core\Lists\ItemHashmap;

class ExpensiveCalculation implements BackgroundProcessDeclaration
{
    public static function retrieveDeclaration(int $version): array
    {
        return [
            function (ApieContext $apieContext, ItemHashmap $payload) {
                return $payload['number'] * $payload['number'];
            },
            function (ApieContext $apieContext, ItemHashmap $payload) {
                return $payload['number'] * $apieContext->getContext(ContextConstants::BACKGROUND_PROCESS);
            },
            function (ApieContext $apieContext, ItemHashmap $payload) {
                return $payload['number'] * $apieContext->getContext(ContextConstants::BACKGROUND_PROCESS);
            }
        ];
    }

    public function getCurrentVersion(): int
    {
        return 1;
    }

    public static function getMaxRetries(int $version): int
    {
        return 42;
    }
}