<?php
namespace Apie\Fixtures\Actions;

use Apie\Core\Attributes\Requires;
use Apie\Core\Attributes\RuntimeCheck;
use Apie\Core\ValueObjects\DatabaseText;
use Apie\Fixtures\Entities\UserWithAddress;
use Apie\Fixtures\Identifiers\UserWithAddressIdentifier;
use Apie\Fixtures\ValueObjects\AddressWithZipcodeCheck;

class FakeLogin
{
    #[RuntimeCheck(new Requires('ftp'))]
    public static function verifyAuthentication(string $username, string $password): UserWithAddress|null
    {
        if ($username === 'user' && $password === 'pass') {
            return new UserWithAddress(
                new AddressWithZipcodeCheck(
                    new DatabaseText('Evergreen Terrace'),
                    new DatabaseText('742'),
                    new DatabaseText('11111'),
                    new DatabaseText('Springfield'),
                ),
                new UserWithAddressIdentifier('00000000-0000-0000-0000-000000000000')
            );
        }

        if ($username === 'error') {
            throw new \RuntimeException('Simulated error during login');
        }

        return null;
    }
}
