<?php
namespace Apie\IntegrationTests\Concerns;

trait RunApplicationTest
{
    abstract public function bootApplication(): void;
    abstract public function cleanApplication(): void;

    public function runApplicationTest(callable $test): void
    {
        $this->bootApplication();
        try {
            $test($this);
        } finally {
            $this->cleanApplication();
        }
    }
}
