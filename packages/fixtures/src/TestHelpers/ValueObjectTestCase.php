<?php
namespace Apie\Fixtures\TestHelpers;

use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

abstract class ValueObjectTestCase extends ObjectTestCase
{
    /**
     * @return class-string<ValueObjectInterface>
     */
    abstract public static function className(): string;

    /**
     * @return array<array-key, array{0: mixed, 1: mixed}>
     */
    abstract public static function provideFromNative(): array;

    #[Test]
    #[DataProvider('provideFromNative')]
    public function it_can_be_instantiated_with_fromNative(mixed $expected, mixed $input): void
    {
        $className = static::className();
        $valueObject = $className::fromNative($input);
        $this->assertInstanceOf($className, $valueObject);
        $this->assertEquals($expected, $valueObject->toNative());
    }
}
