<?php
namespace Apie\Tests\TextValueObjects;

use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\Fixtures\TestHelpers\TestWithOpenapiSchema;
use Apie\TextValueObjects\CompanyName;
use PHPUnit\Framework\TestCase;

class CompanyNameTest extends TestCase
{
    use TestWithFaker;
    use TestWithOpenapiSchema;

    /**
     * @test
     * @dataProvider inputProvider
     */
    public function fromNative_allows_many_names(string $expected, string $input)
    {
        $testItem = CompanyName::fromNative($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    /**
     * @test
     * @dataProvider inputProvider
     */
    public function it_allows_many_names(string $expected, string $input)
    {
        $testItem = new CompanyName($input);
        $this->assertEquals($expected, $testItem->toNative());
    }

    public function inputProvider()
    {
        yield ['42', '42'];
        yield ['Team 17', '   Team 17   '];
        yield ['McDonalds', 'McDonalds'];
    }

    /**
     * @test
     * @dataProvider invalidProvider
     */
    public function it_refuses_empty_strings(string $input)
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        new CompanyName($input);
    }

    /**
     * @test
     * @dataProvider invalidProvider
     */
    public function it_refuses_empty_strings_with_fromNative(string $input)
    {
        $this->expectException(InvalidStringForValueObjectException::class);
        CompanyName::fromNative($input);
    }

    public function invalidProvider()
    {
        yield [''];
        yield [' '];
        yield ["          \t\n\r\n"];
    }

    /**
     * @test
     */
    public function it_works_with_schema_generator()
    {
        $this->runOpenapiSchemaTestForCreation(
            CompanyName::class,
            'CompanyName-post',
            [
                'type' => 'string',
                'format' => 'companyname',
                'pattern' => true,
            ]
        );
    }

    /**
     * @test
     */
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(CompanyName::class);
    }
}
