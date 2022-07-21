<?php
namespace Apie\Tests\TextValueObjects;

use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\Fixtures\TestHelpers\TestWithOpenapiSchema;
use Apie\TextValueObjects\LastName;
use PHPUnit\Framework\TestCase;

class LastNameTest extends TestCase
{
    use TestWithFaker;
    use TestWithOpenapiSchema;

    /**
     * @test
     * @dataProvider inputProvider
     */
    public function fromNative_allows_many_names(string $expected, string $input)
    {
        $testItem = LastName::fromNative($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    /**
     * @test
     * @dataProvider inputProvider
     */
    public function it_allows_many_names(string $expected, string $input)
    {
        $testItem = new LastName($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    public function inputProvider()
    {
        yield ['Jordan', 'Jordan'];
        yield ['van Oranje Nassouwe', '   van Oranje Nassouwe   '];
        yield ['d`Ancona', 'd`Ancona'];
    }

    /**
     * @test
     * @dataProvider invalidProvider
     */
    public function it_refuses_empty_strings(string $input)
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        new LastName($input);
    }

    /**
     * @test
     * @dataProvider invalidProvider
     */
    public function it_refuses_empty_strings_with_fromNative(string $input)
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        LastName::fromNative($input);
    }

    public function invalidProvider()
    {
        yield [''];
        yield [' '];
        yield ["          \t\n\r\n"];
        yield ['van 
        Newline'];
    }

    /**
     * @test
     */
    public function it_works_with_schema_generator()
    {
        $this->runOpenapiSchemaTestForCreation(
            LastName::class,
            'LastName-post',
            [
                'type' => 'string',
                'format' => 'lastname',
                'pattern' => true,
            ]
        );
    }

    /**
     * @test
     */
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(LastName::class);
    }
}
