<?php

namespace App\ApiePlayground\Types\Policies;


class AuditLogPolicy
{
    public function __call(string $name, array $arguments): bool|null
    {
        return true;
    }
}