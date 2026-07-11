<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Actions;

use Apie\Core\Attributes\Context;
use Apie\Core\Attributes\Description;
use Apie\Core\Attributes\Route;
use Apie\Core\BoundedContext\BoundedContext;
use Apie\Core\Context\ApieContext;
use Apie\Core\ContextConstants;
use Apie\Core\Datalayers\ApieDatalayer;
use Apie\Core\Entities\EntityInterface;
use Apie\IanaValueObjects\LanguageAndRegion;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\UserIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\User;
use Exception;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Authentication
{
    #[Description('Login as user with username and password, returns null if authentication fails')]
    public function verifyAuthentication(
        #[Context()] ApieDatalayer $apieDatalayer,
        #[Context()] BoundedContext $boundedContext,
        string $username,
        string $password
    ): ?User {
        try {
            /** @var UserIdentifier @userId */
            $userId = UserIdentifier::fromNative($username);
            $user = $apieDatalayer->find($userId, $boundedContext->getId());
        } catch (Exception) {
            return null;
        }
        return $user->verify($password) ? $user : null;
    }

    #[Description('Display the current logged in user, returns null if not logged in')]
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

    public function acceptLocale(#[Context(ContextConstants::ACCEPT_LOCALE)] ?string $locale = null): ?string
    {
        return $locale;
    }

    public function locale(#[Context(ContextConstants::LOCALE)] ?string $locale = null): ?string
    {
        return $locale;
    }

    public function dataLocale(#[Context(ContextConstants::DATA_LOCALE)] ?string $locale = null): ?string
    {
        return $locale;
    }

    public function localeObject(#[Context] ?LanguageAndRegion $locale = null): ?LanguageAndRegion
    {
        return $locale;
    }

    public function isThisMe(#[Context] ApieContext $apieContext, UserIdentifier $userId): bool
    {
        $authenticated = $apieContext->getContext(ContextConstants::AUTHENTICATED_USER, false);
        if (!$authenticated) {
            return false;
        }
        return $authenticated->getId()->toNative() === $userId->toNative();
    }
}
