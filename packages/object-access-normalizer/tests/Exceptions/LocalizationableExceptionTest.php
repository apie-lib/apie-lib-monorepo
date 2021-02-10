<?php


namespace Apie\Tests\ObjectAccessNormalizer\Exceptions;

use Apie\ObjectAccessNormalizer\Exceptions\CouldNotConvertException;
use Apie\ObjectAccessNormalizer\Exceptions\NameNotFoundException;
use Apie\ObjectAccessNormalizer\Exceptions\ObjectAccessException;
use Apie\ObjectAccessNormalizer\Exceptions\ObjectWriteException;
use Apie\ObjectAccessNormalizer\Exceptions\ValidationException;
use Apie\ObjectAccessNormalizer\Getters\ReflectionMethodGetter;
use Apie\ObjectAccessNormalizer\Setters\ReflectionMethodSetter;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use RuntimeException;

class LocalizationableExceptionTest extends TestCase
{
    public function testCouldNotConvert()
    {
        $testItem = new CouldNotConvertException(__CLASS__, 'int');
        $actual = $testItem->getI18n();
        $this->assertSame('serialize.conversion_error', $actual->getMessageString());
        $this->assertEquals(['wanted' => __CLASS__, 'given' => 'int'], $actual->getReplacements());
    }

    public function testNameNotFound()
    {
        $testItem = new NameNotFoundException('Wally');
        $actual = $testItem->getI18n();
        $this->assertSame('general.name_not_found', $actual->getMessageString());
        $this->assertEquals(['name' => 'Wally'], $actual->getReplacements());
    }

    public function testObjectAccessError()
    {
        $testItem = new ObjectAccessException(
            new ReflectionMethodGetter(new ReflectionMethod(__METHOD__)),
            'access',
            new RuntimeException('Internal error')
        );
        $actual = $testItem->getI18n();
        $this->assertSame('serialize.read', $actual->getMessageString());
        $this->assertEquals(['name' => __FUNCTION__, 'previous' => 'Internal error'], $actual->getReplacements());
    }

    public function testObjectWriteError()
    {
        $testItem = new ObjectWriteException(
            new ReflectionMethodSetter(new ReflectionMethod(__METHOD__)),
            'access',
            new RuntimeException('Internal error')
        );
        $actual = $testItem->getI18n();
        $this->assertSame('serialize.write', $actual->getMessageString());
        $this->assertEquals(['name' => __FUNCTION__, 'previous' => 'Internal error'], $actual->getReplacements());
    }

    public function testValidationError()
    {
        $testItem = new ValidationException(['test' => ['example']]);
        $actual = $testItem->getI18n();
        $this->assertSame('general.validation', $actual->getMessageString());
        $this->assertEquals(['errors' => ['test' => ['example']]], $actual->getReplacements());
    }
}
