<?php
namespace Apie\Tests\Faker;

use Apie\CommonValueObjects\Ranges\DateTimeRange;
use Apie\CommonValueObjects\Enums\Gender;
use Apie\CommonValueObjects\Identifiers\KebabCaseSlug;
use Apie\CommonValueObjects\Identifiers\PascalCaseSlug;
use Apie\CommonValueObjects\Identifiers\Slug;
use Apie\CommonValueObjects\Identifiers\Uuid;
use Apie\CommonValueObjects\Names\FirstName;
use Apie\CommonValueObjects\Names\LastName;
use Apie\CommonValueObjects\Texts\DatabaseText;
use Apie\CommonValueObjects\Texts\NonEmptyString;
use Apie\CommonValueObjects\Texts\SmallDatabaseText;
use Apie\DateValueObjects\Time;
use Apie\Faker\ApieObjectFaker;
use DateTimeImmutable;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\Finder\Finder;
use UnitEnum;

class ApieObjectFakerTest extends TestCase
{
    private function givenAFakerWithApieObjectFaker(): Generator
    {
        $faker = Factory::create();
        $faker->addProvider(ApieObjectFaker::createWithDefaultFakers($faker));
        return $faker;
    }

    /**
     * @test
     * @dataProvider dateValueObjectsProvider
     */
    public function it_can_fake_a_date_value_object(string $classToTest)
    {
        $faker = $this->givenAFakerWithApieObjectFaker();
        for ($i = 0; $i < 1000; $i++) {
            $result = $faker->fakeClass($classToTest);
            $this->assertInstanceOf(DateTimeImmutable::class, $result->toDate());

            $result = $faker->unique()->fakeClass($classToTest);
            $this->assertInstanceOf(DateTimeImmutable::class, $result->toDate());
        }
    }

    public function dateValueObjectsProvider(): iterable
    {
        $path = dirname((new ReflectionClass(Time::class))->getFileName());
        foreach (Finder::create()->files()->name('*.php')->depth(0)->in($path) as $file) {
            yield ['Apie\\DateValueObjects\\' . $file->getBasename('.php')];
        }
    }

    /**
     * @test
     * @dataProvider compositeValueObjectProvider
     */
    public function it_can_fake_composite_value_objects(string $classToTest)
    {
        $faker = $this->givenAFakerWithApieObjectFaker();
        for ($i = 0; $i < 1000; $i++) {
            $result = $faker->fakeClass($classToTest);
            $this->assertInstanceOf($classToTest, $result);
        }
    }

    public function compositeValueObjectProvider(): iterable
    {
        yield [DateTimeRange::class];
    }

    /**
     * @test
     * @dataProvider enumProvider
     */
    public function it_can_fake_an_enum(string $classToTest)
    {
        $faker = $this->givenAFakerWithApieObjectFaker();
        for ($i = 0; $i < 1000; $i++) {
            $result = $faker->fakeClass($classToTest);
            $this->assertInstanceOf(UnitEnum::class, $result);
        }
    }

    public function enumProvider()
    {
        yield [Gender::class];
    }

    /**
     * @test
     * @dataProvider stringValueObjectsProvider
     */
    public function it_can_fake_a_string_with_regex_value_object(string $classToTest)
    {
        $faker = $this->givenAFakerWithApieObjectFaker();
        for ($i = 0; $i < 1000; $i++) {
            $result = $faker->fakeClass($classToTest);
            $this->assertStringMatchesFormat('%s', $result->toNative());
        }
    }

    public function stringValueObjectsProvider(): iterable
    {
        yield [KebabCaseSlug::class];
        yield [PascalCaseSlug::class];
        yield [Slug::class];
        yield [Uuid::class];
        yield [NonEmptyString::class];
        yield [DatabaseText::class];
        yield [SmallDatabaseText::class];
        yield [FirstName::class];
        yield [LastName::class];
    }
}