<?php
namespace Apie\Fixtures\TestHelpers;

use Apie\Core\Attributes\FakeCount;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

abstract class ObjectTestCase extends TestCase
{
    use TestWithOpenapiSchema;
    use TestWithFaker;
    use TestWithDoctrineEntityDatalayer;

    /**
     * @return class-string<object>
     */
    abstract public static function className(): string;

    abstract public static function getOpenApiSchemaForCreation(): array;

    protected static function testCount(): int
    {
        $refl = new \ReflectionClass(static::className());
        foreach ($refl->getAttributes(FakeCount::class) as $attribute) {
            return $attribute->newInstance()->count;
        }

        return 500;
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

    #[Test]
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(static::className(), interval: static::testCount());
    }
}
