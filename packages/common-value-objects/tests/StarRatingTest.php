<?php
namespace Apie\Tests\CommonValueObjects;

use Apie\CommonValueObjects\StarRating;
use Apie\Core\Exceptions\InvalidTypeException;
use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\Fixtures\TestHelpers\TestWithOpenapiSchema;
use Generator;
use PHPUnit\Framework\TestCase;

class StarRatingTest extends TestCase
{
    use TestWithOpenapiSchema;
    use TestWithFaker;

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_works_with_schema_generator()
    {
        $this->runOpenapiSchemaTestForCreation(
            StarRating::class,
            'StarRating-post',
            [
                'type' => 'number',
                'format' => 'integer',
                'minimum' => 0,
                'maximum' => 5,
            ]
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(StarRating::class);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_be_created_with_fromNative()
    {
        $testItem = StarRating::fromNative(0);
        $this->assertSame(0, $testItem->toNative());
        $testItem = StarRating::fromNative('3');
        $this->assertSame(3, $testItem->toNative());
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('provideInvalidInputIntegers')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function constructor_only_allows_integer_between_0_and_5(int $input)
    {
        $this->expectException(InvalidTypeException::class);
        new StarRating($input);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('provideInvalidInput')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_does_not_allow_invalid_input(mixed $input)
    {
        $this->expectException(InvalidTypeException::class);
        StarRating::fromNative($input);
    }

    public static function provideInvalidInputIntegers(): Generator
    {
        yield [-1];
        yield [7];
    }

    public static function provideInvalidInput(): Generator
    {
        yield from self::provideInvalidInputIntegers();
        yield ['-1'];
        yield [5.9];
        yield ['this is bogus'];
        yield [[]];
    }
}
