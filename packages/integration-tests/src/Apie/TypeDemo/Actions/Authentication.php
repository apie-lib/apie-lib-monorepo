<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Actions;

use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Datalayers\ApieDatalayer;
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
            $user = $this->apieDatalayer->find(UserIdentifier::fromNative($username), new BoundedContextId('types'));
        } catch (Exception) {
            return null;
        }
        return $user->verify($password) ? $user : null;
    }
}
