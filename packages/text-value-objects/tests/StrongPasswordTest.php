<?php
namespace Apie\Tests\TextValueObjects;

use Apie\Core\Randomizer\SecureRandomizer;
use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\Fixtures\TestHelpers\TestWithOpenapiSchema;
use Apie\TextValueObjects\StrongPassword;
use PHPUnit\Framework\TestCase;

class StrongPasswordTest extends TestCase
{
    use TestWithOpenapiSchema;
    use TestWithFaker;
    /**
     * @test
     * @dataProvider inputProvider
     */
    public function fromNative_allows_all_strings_that_are_not_too_long(string $expected, string $input)
    {
        $testItem = StrongPassword::fromNative($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    /**
     * @test
     * @dataProvider inputProvider
     */
    public function it_allows_all_strings_that_are_not_too_long(string $expected, string $input)
    {
        $testItem = new StrongPassword($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    public function inputProvider()
    {
        yield ['&#12azAZ', '&#12azAZ'];
        yield ['&#12azAZ', '   &#12azAZ   '];
    }

    /**
     * @test
     * @dataProvider invalidProvider
     */
    public function it_refuses_strings_that_are_not_strong_passwords(string $input)
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        new StrongPassword($input);
    }

    /**
     * @test
     * @dataProvider invalidProvider
     */
    public function it_refuses_strings_that_are_not_strong_passwords_with_fromNative(string $input)
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        StrongPassword::fromNative($input);
    }

    public function invalidProvider()
    {
        yield [str_repeat('1', 300)];
        yield [''];
    }

    /**
     * @test
     */
    public function it_works_with_schema_generator()
    {
        $this->runOpenapiSchemaTestForCreation(
            StrongPassword::class,
            'StrongPassword-post',
            [
                'type' => 'string',
                'format' => 'password',
                'pattern' => StrongPassword::getRegularExpression(),
            ]
        );
    }

    /**
     * @test
     */
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(StrongPassword::class);
    }

    /**
     * @test
     */
    public function it_can_render_passwords_securely()
    {
        $randomizer = new SecureRandomizer();
        $rendered = [];
        for($i = 0; $i < 100; $i++) {
            mt_srand(42);
            $password = StrongPassword::createRandom($randomizer)->toNative();
            $this->assertArrayNotHasKey($password, $rendered);
            $rendered[$password] = true;
        }
    }
}
