<?php
namespace Apie\TextValueObjects;

use Apie\Core\Attributes\FakeMethod;
use Apie\Core\Attributes\ProvideIndex;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;
use Apie\Core\ValueObjects\IsStringValueObject;
use Faker\Generator;
use Stringable;

#[FakeMethod('createRandom')]
#[ProvideIndex('getIndexes')]
final class EncryptedPassword implements StringValueObjectInterface
{
    use IsStringValueObject;

    public static function fromUnencryptedPassword(Stringable|string $password): self
    {
        $password = (string) $password;
        return new self(password_hash($password, null));
    }

    public function verifyUnencryptedPassword(Stringable|string $password): bool
    {
        $password = (string) $password;
        return password_verify($password, $this->internal);
    }

    /**
     * @return array<string, int>
     */
    public static function getIndex(): array
    {
        return [];
    }

    public static function createRandom(Generator $generator): self
    {
        return self::fromUnencryptedPassword($generator->password(6, 42));
    }
}
