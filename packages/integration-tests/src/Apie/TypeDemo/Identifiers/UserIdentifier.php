<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Identifiers;

use Apie\CommonValueObjects\Email;
use Apie\Core\Identifiers\Identifier;
use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Core\Permissions\AllPermission;
use Apie\Core\Permissions\PermissionInterface;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\User;
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

    public function toPermission(): PermissionInterface
    {
        return new AllPermission(
            Identifier::fromNative(
                str_replace(['@', '.'], ['at' ,'dot'], $this->toNative())
            )
        );
    }
}
