<?php
namespace Apie\IntegrationTests\Concerns;

trait ItRunsApplications
{
    abstract public function bootApplication(): void;
    abstract public function cleanApplication(): void;

    public function ItRunsApplications(callable $test): void
    {
        $this->bootApplication();
        try {
            $test($this);
        } finally {
            $this->cleanApplication();
        }
    }
}
