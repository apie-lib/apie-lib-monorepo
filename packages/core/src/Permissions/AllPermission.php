<?php
namespace Apie\Core\Permissions;

use Apie\Core\Identifiers\Identifier;
use Apie\Core\Lists\PermissionList;

final class AllPermission implements PermissionInterface
{
    public function __construct(private readonly Identifier $identifier)
    {
    }

    public function getPermissionIdentifiers(): PermissionList
    {
        return new PermissionList([$this->identifier . ':read', $this->identifier . ':write']);
    }
}
