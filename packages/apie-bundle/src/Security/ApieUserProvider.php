<?php

namespace Apie\ApieBundle\Security;

use Apie\ApieBundle\Security\Interfaces\CheckLoginStatusInterface;
use Apie\ApieBundle\Security\ValueObjects\ApieUserDecoratorIdentifier;
use Apie\Common\ApieFacade;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\ValueObjects\Utils;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ApieUserProvider implements UserProviderInterface
{
    public function __construct(private readonly ApieFacade $apieFacade)
    {
    }

    /**
     * @template T of EntityInterface
     * @param ApieUserDecorator<T> $user
     * @return ApieUserDecorator<T>
     */
    public function refreshUser(UserInterface $user): ApieUserDecorator
    {
        if (!$user instanceof ApieUserDecorator) {
            throw new UnsupportedUserException(get_debug_type($user) . ' is not supported');
        }
        $entity = $user->getEntity();
        if ($entity instanceof CheckLoginStatusInterface) {
            if ($entity->isDisabled()) {
                throw new UnsupportedUserException('User ' . Utils::toString($entity->getId()) . ' is disabled, logging out');
            }
        }
        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return $class === ApieUserDecorator::class;
    }

    /**
     * @return ApieUserDecorator<EntityInterface>
     */
    public function loadUserByIdentifier(string $identifier): ApieUserDecorator
    {
        $identifier = new ApieUserDecoratorIdentifier($identifier);
        $boundedContextId = $identifier->getBoundedContextId();
        $entity = $this->apieFacade->find($identifier->getIdentifier(), $boundedContextId);
        return new ApieUserDecorator($identifier, $entity);
    }
}