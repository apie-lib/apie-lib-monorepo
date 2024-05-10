<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Actions;

use Apie\Core\Attributes\Context;
use Apie\Core\Attributes\Route;
use Apie\Core\BoundedContext\BoundedContext;
use Apie\Core\Datalayers\ApieDatalayer;
use Apie\Core\Entities\EntityInterface;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\UserIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\User;
use Exception;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Authentication
{
    public function verifyAuthentication(
        #[Context()] ApieDatalayer $apieDatalayer,
        #[Context()] BoundedContext $boundedContext,
        string $username,
        string $password
    ): ?User {
        try {
            /** @var UserIdentifier @userId */
            $userId = UserIdentifier::fromNative($username);
            $user = $apieDatalayer->find($userId, $boundedContext);
        } catch (Exception) {
            return null;
        }
        return $user->verify($password) ? $user : null;
    }

    #[Route('/me')]
    #[Route('/profile', target: Route::CMS)]
    public function currentUser(#[Context('authenticated')] ?EntityInterface $currentUser = null): ?EntityInterface
    {
        return $currentUser;
    }

    /**
     * @return array<string|int, mixed>
     */
    public function currentSession(#[Context] SessionInterface $sessionInterface): array
    {
        return $sessionInterface->all();
    }
}
