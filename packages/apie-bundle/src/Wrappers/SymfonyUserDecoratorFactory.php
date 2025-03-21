<?php

namespace Apie\ApieBundle\Wrappers;

use Apie\ApieBundle\Security\SymfonyUserDecorator;
use Apie\ApieBundle\Security\SymfonyUserWithPermissionDecorator;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\Permissions\PermissionInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class SymfonyUserDecoratorFactory
{
    public function create(UserInterface $user): SymfonyUserDecorator|EntityInterface
    {
        if ($user instanceof EntityInterface) {
            return $user;
        }
        if ($user instanceof PermissionInterface) {
            return new SymfonyUserWithPermissionDecorator($user);
        }
        return new SymfonyUserDecorator($user);
    }
}
