<?php
namespace App\ApiePlayground\Permission\Actions;

use Apie\Core\Datalayers\ApieDatalayer;
use Apie\CommonValueObjects\Email;
use Apie\CommonValueObjects\FullName;
use Apie\Core\Attributes\Context;
use Apie\Core\Attributes\NotLoggedIn;
use Apie\Core\Attributes\Route;
use Apie\Core\Attributes\RuntimeCheck;
use Apie\Core\BoundedContext\BoundedContext;
use Apie\Core\Context\ApieContext;
use Apie\Core\Datalayers\Search\QuerySearch;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\Lists\StringHashmap;
use Apie\Serializer\Exceptions\ValidationException;
use App\ApiePlayground\Permission\Enums\UserRole;
use App\ApiePlayground\Permission\Resources\User;
use LogicException;
use ReflectionClass;

class LoginTest
{
    #[RuntimeCheck(new NotLoggedIn())]
    public function verifyAuthentication(
        #[Context()]
        ApieDatalayer $apieDatalayer,
        #[Context()]
        BoundedContext $boundedContext,
        #[Context()]
        ApieContext $apieContext,
        FullName $fullName,
        Email $email,
        UserRole $userRole
    ): User {
        $results = $apieDatalayer->all(
            new ReflectionClass(User::class),
            $boundedContext->getId()
        )->toPaginatedResult(
            new QuerySearch(0, 1, null, new StringHashmap(['email' => $email->toNative()]), apieContext: $apieContext)
        );
        $iterator = $results->getIterator();
        if ($iterator->valid()) {
            $user = $iterator->current();
            if (!$user instanceof User || $user->isDisabled()) {
                throw ValidationException::createFromArray([
                    'email' => new LogicException('User is blocked and can not login!'),
                ]);
            }
            $user->setFullName($fullName);
            $user->setUserRole($userRole);
            $user = $apieDatalayer->persistExisting($user, $boundedContext->getId());
        } else {
            $user = new User($email, $fullName, $userRole);
            $user = $apieDatalayer->persistNew($user, $boundedContext->getId());
        }
        return $user;
    }

    #[Route('/me')]
    #[Route('/profile', target: Route::CMS)]
    public function currentUser(#[Context('authenticated')] ?EntityInterface $currentUser = null): ?EntityInterface
    {
        return $currentUser;
    }
}