<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Policies;

use Apie\Core\BackgroundProcess\SequentialBackgroundProcess;
use Apie\Core\Context\ApieContext;
use Apie\Core\Enums\ConsoleCommand;

class SequentialBackgroundProcessPolicy
{
    public function staticRunStep(ApieContext $apieContext): bool
    {
        return $apieContext->hasContext(ConsoleCommand::class) || $apieContext->hasContext('route-gen');
    }

    public function canViewAny(): bool
    {
        return true;
    }
    
    public function canView(SequentialBackgroundProcess $resource): bool
    {
        return true;
    }
}
