<?php

namespace App\ApiePlayground\Permission\Policies;


class AuditLogPolicy
{
    public function __call(string $name, array $arguments): bool|null
    {
        return true;
    }
}