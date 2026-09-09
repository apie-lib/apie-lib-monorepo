<?php

namespace App\ApiePlayground\Example\Policies;


class AuditLogPolicy
{
    public function __call(string $name, array $arguments): bool|null
    {
        return true;
    }
}