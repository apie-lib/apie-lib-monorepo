<?php
namespace App\ApiePlayground\Types\Concerns;

use DateTimeImmutable;
use DateTimeInterface;

trait FlyingAnimal
{
    private ?DateTimeInterface $flying = null;
    public function fly(): void
    {
        if ($this->flying === null) {
            $this->flying = new DateTimeImmutable();
        }
    }

    public function land(): void
    {
        $this->flying = null;
    }

    public function isFlying(): bool
    {
        return $this->flying !== null;
    }
}