<?php
namespace Apie\IntegrationTests\Concerns;

use Apie\IntegrationTests\Applications\Symfony\SymfonyTestApplication;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;

trait CreatesApplications
{
    public function createTestSymfonyApplication(): ?TestApplicationInterface
    {
        return new SymfonyTestApplication();
    }

    public function createTestLaravelApplication(): ?TestApplicationInterface
    {
        return null;
    }
}