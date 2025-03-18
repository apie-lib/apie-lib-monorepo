<?php
namespace Apie\Tests\TextValueObjects;

use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\Fixtures\TestHelpers\TestWithOpenapiSchema;
use Apie\TextValueObjects\EncryptedPassword;
use PHPUnit\Framework\TestCase;

class EncryptedPasswordTest extends TestCase
{
    use TestWithFaker;
    use TestWithOpenapiSchema;

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_hash_and_verify_passwords()
    {
        $unencrypted = 'This is a string';
        $testItem = EncryptedPassword::fromUnencryptedPassword($unencrypted);
        $this->assertTrue($testItem->verifyUnencryptedPassword($unencrypted));
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('inputProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function fromNative_allows_many_names(string $expected, string $input)
    {
        $testItem = EncryptedPassword::fromNative($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('inputProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_allows_many_names(string $expected, string $input)
    {
        $testItem = new EncryptedPassword($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    public static function inputProvider()
    {
        yield [
            '$2y$10$zL2UWxcQ9.lYpoI.yTjl9eYdO4hv.jb/iwCpathPmgpV38hkGzBAW',
            '$2y$10$zL2UWxcQ9.lYpoI.yTjl9eYdO4hv.jb/iwCpathPmgpV38hkGzBAW'
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('invalidProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_refuses_random_strings(string $input)
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        new EncryptedPassword($input);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('invalidProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
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

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_works_with_schema_generator()
    {
        $this->runOpenapiSchemaTestForCreation(
            EncryptedPassword::class,
            'EncryptedPassword-post',
            [
                'type' => 'string',
                'format' => 'encryptedpassword',
            ]
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(EncryptedPassword::class, interval: 15);
    }
}
