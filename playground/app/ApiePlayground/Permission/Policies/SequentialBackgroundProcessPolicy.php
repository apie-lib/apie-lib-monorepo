<?php

namespace App\ApiePlayground\Permission\Policies;

class SequentialBackgroundProcessPolicy
{
    public function __call(string $name, array $arguments): bool|null
    {
        return true;
    }
}