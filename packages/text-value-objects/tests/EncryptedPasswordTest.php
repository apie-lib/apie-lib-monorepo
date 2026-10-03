<?php
namespace Apie\Tests\TextValueObjects;

use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Fixtures\TestHelpers\ValueObjectTestCase;
use Apie\TextValueObjects\EncryptedPassword;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class EncryptedPasswordTest extends ValueObjectTestCase
{
    public static function className(): string
    {
        return EncryptedPassword::class;
    }

    public static function getOpenApiSchemaForCreation(): array
    {
        return [
            'type' => 'string',
            'format' => 'encryptedpassword',
            'description' => true,
            'pattern' => true,
        ];
    }

    public static function provideFromNative(): array
    {
        return [
            'an encrypted password' => [
                '$2y$10$zL2UWxcQ9.lYpoI.yTjl9eYdO4hv.jb/iwCpathPmgpV38hkGzBAW',
                '$2y$10$zL2UWxcQ9.lYpoI.yTjl9eYdO4hv.jb/iwCpathPmgpV38hkGzBAW'
            ]
        ];
    }
    #[Test]
    public function it_can_hash_and_verify_passwords()
    {
        $unencrypted = 'This is a string';
        $testItem = EncryptedPassword::fromUnencryptedPassword($unencrypted);
        $this->assertTrue($testItem->verifyUnencryptedPassword($unencrypted));
    }

    #[DataProvider('provideFromNative')]
    #[Test]
    public function it_allows_many_names(string $expected, string $input)
    {
        $testItem = new EncryptedPassword($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    #[DataProvider('invalidProvider')]
    #[Test]
    public function it_refuses_random_strings(string $input)
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        new EncryptedPassword($input);
    }

    #[DataProvider('invalidProvider')]
    #[Test]
    public function it_refuses_random_strings_with_fromNative(string $input)
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        EncryptedPassword::fromNative($input);
    }

    public static function invalidProvider()
    {
        yield [''];
        yield [' '];
        yield ["          \t\n\r\n"];
        yield ['   $2y$10$zL2UWxcQ9.lYpoI.yTjl9eYdO4hv.jb/iwCpathPmgpV38hkGzBAW   '];
    }
}
