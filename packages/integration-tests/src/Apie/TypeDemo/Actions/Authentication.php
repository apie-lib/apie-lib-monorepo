<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Actions;

use Apie\Core\Attributes\Context;
use Apie\Core\Attributes\Route;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Datalayers\ApieDatalayer;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\Enums\RequestMethod;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\UserIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\User;
use Exception;

class Authentication
{
    public function __construct(private readonly ApieDatalayer $apieDatalayer)
    {
    }
    public function verifyAuthentication(string $username, string $password): ?User
    {
        try {
            /** @var UserIdentifier @userId */
            $userId = UserIdentifier::fromNative($username);
            $user = $this->apieDatalayer->find($userId, new BoundedContextId('types'));
        } catch (Exception) {
            return null;
        }
        return $user->verify($password) ? $user : null;
    }

    #[Route('/me', RequestMethod::GET)]
    #[Route('/profile', target: Route::CMS)]
    public function currentUser(#[Context('authenticated')] ?EntityInterface $currentUser = null): EntityInterface
    {
        return $currentUser;
    }
}
