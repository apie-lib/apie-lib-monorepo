<?php
namespace App\ApiePlayground\Permission\Actions;

use Apie\Core\Datalayers\ApieDatalayer;
use Apie\Core\Datalayers\BoundedContextAwareApieDatalayer;
use Apie\CommonValueObjects\Email;
use Apie\CommonValueObjects\FullName;
use Apie\Core\Attributes\Context;
use Apie\Core\Attributes\NotLoggedIn;
use Apie\Core\Attributes\RuntimeCheck;
use Apie\Core\BoundedContext\BoundedContext;
use Apie\Core\Context\ApieContext;
use Apie\Core\Datalayers\Search\QuerySearch;
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
        BoundedContextAwareApieDatalayer $apieDatalayer,
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
            $boundedContext
        )->toPaginatedResult(
            new QuerySearch(0, 1, null, new StringHashmap(['email' => $email->toNative()])),
            $apieContext
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
            $user = $apieDatalayer->persistExisting($user, $boundedContext);
        } else {
            $user = new User($email, $fullName, $userRole);
            $user = $apieDatalayer->persistNew($user, $boundedContext);
        }
        return $user;
    }
}