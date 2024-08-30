<?php
namespace App\ApiePlayground\Types\Concerns;

use DateTimeImmutable;
use DateTimeInterface;

trait SwimmingAnimal
{
    private ?DateTimeInterface $swimming = null;
    public function dive(): void
    {
        if ($this->swimming === null) {
            $this->swimming = new DateTimeImmutable();
        }
    }

    public function ascend(): void
    {
        $this->swimming = null;
    }

    public function isSwimming(): bool
    {
        return $this->swimming !== null;
    }
}