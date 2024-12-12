<?php
namespace Apie\TextValueObjects;

use Apie\Core\Attributes\FakeMethod;
use Apie\Core\Attributes\ProvideIndex;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;
use Apie\Core\ValueObjects\IsStringWithRegexValueObject;
use Faker\Generator;
use Stringable;

#[FakeMethod('createRandom')]
#[ProvideIndex('getIndexes')]
final class EncryptedPassword implements StringValueObjectInterface
{
    use IsStringWithRegexValueObject;

    public static function fromUnencryptedPassword(Stringable|string $password): self
    {
        $password = (string) $password;
        return new self(password_hash($password, null));
    }

    public static function getRegularExpression(): string
    {
        return '/^[$]2[abxy]?[$](?:0[4-9]|[12][0-9]|3[01])[$][.\/0-9a-zA-Z]{53,60}$/';
    }

    public function verifyUnencryptedPassword(Stringable|string $password): bool
    {
        $password = (string) $password;
        return password_verify($password, $this->internal);
    }

    /**
     * @return array<string, int>
     */
    public static function getIndexes(): array
    {
        return [];
    }

    public static function createRandom(Generator $generator): self
    {
        return self::fromUnencryptedPassword($generator->password(6, 42));
    }
}
