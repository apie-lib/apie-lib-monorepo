<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Identifiers;

use Apie\CommonValueObjects\Email;
use Apie\Core\Identifiers\IdentifierInterface;
use Apie\IntegrationTests\Apie\TypeDemo\Entities\User;
use ReflectionClass;

/**
 * @implements IdentifierInterface<User>
 */
final class UserIdentifier extends Email implements IdentifierInterface
{
    public static function getReferenceFor(): ReflectionClass
    {
        return new ReflectionClass(User::class);
    }
}
