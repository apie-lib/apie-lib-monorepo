<?php
namespace Apie\Fixtures\TestHelpers;

use Apie\Core\Attributes\FakeCount;
use Apie\Core\ValueObjects\ValueObjectInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

abstract class ValueObjectTestCase extends TestCase
{
    use TestWithOpenapiSchema;
    use TestWithFaker;

    /**
     * @return class-string<ValueObjectInterface>
     */
    abstract public static function className(): string;

    /**
     * @return array<array-key, array{0: mixed, 1: mixed}>
     */
    abstract public static function provideFromNative(): array;

    abstract public static function getOpenApiSchemaForCreation(): array;

    protected static function testCount(): int
    {
        $refl = new \ReflectionClass(static::className());
        foreach ($refl->getAttributes(FakeCount::class) as $attribute) {
            return $attribute->newInstance()->count;
        }

        return 1000;
    }

    #[Test]
    #[DataProvider('provideFromNative')]
    public function it_can_be_instantiated_with_fromNative(mixed $expected, mixed $input): void
    {
        $className = static::className();
        $valueObject = $className::fromNative($input);
        $this->assertInstanceOf($className, $valueObject);
        $this->assertEquals($expected, $valueObject->toNative());
    }

    #[Test]
    public function it_works_with_schema_generator()
    {
        $this->runOpenapiSchemaTestForCreation(
            static::className(),
            (new \ReflectionClass(static::className()))->getShortName() . '-post',
            static::getOpenApiSchemaForCreation()
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(static::className(), interval: static::testCount());
    }
}
