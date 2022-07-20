<?php
namespace Apie\Tests\TextValueObjects;

use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\Fixtures\TestHelpers\TestWithOpenapiSchema;
use Apie\TextValueObjects\SmallDatabaseText;
use PHPUnit\Framework\TestCase;

class SmallDatabaseTextTest extends TestCase
{
    use TestWithOpenapiSchema;
    use TestWithFaker;
    /**
     * @test
     * @dataProvider inputProvider
     */
    public function fromNative_allows_all_strings_that_are_not_too_long(string $expected, string $input)
    {
        $testItem = SmallDatabaseText::fromNative($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    /**
     * @test
     * @dataProvider inputProvider
     */
    public function it_allows_all_strings_that_are_not_too_long(string $expected, string $input)
    {
        $testItem = new SmallDatabaseText($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    public function inputProvider()
    {
        yield ['', '    '];
        yield ['', str_repeat(' ', 300)];
        yield ['test', 'test'];
        yield ['trimmed', '   trimmed   '];
    }

    /**
     * @test
     * @dataProvider invalidProvider
     */
    public function it_refuses_strings_that_are_too_long(string $input)
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        new SmallDatabaseText($input);
    }

    /**
     * @test
     * @dataProvider invalidProvider
     */
    public function it_refuses_strings_that_are_too_long_with_fromNative(string $input)
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        SmallDatabaseText::fromNative($input);
    }

    public function invalidProvider()
    {
        yield [str_repeat('1', '300')];
    }

    /**
     * @test
     */
    public function it_works_with_schema_generator()
    {
        $this->runOpenapiSchemaTestForCreation(
            SmallDatabaseText::class,
            'SmallDatabaseText-post',
            [
                'type' => 'string',
                'format' => 'smalldatabasetext',
                'pattern' => true,
            ]
        );
    }

    /**
     * @test
     */
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(SmallDatabaseText::class);
    }
}
