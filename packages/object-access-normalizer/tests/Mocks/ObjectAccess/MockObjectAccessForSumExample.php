<?php
namespace Apie\Tests\ObjectAccessNormalizer\Mocks\ObjectAccess;

use Apie\ObjectAccessNormalizer\ObjectAccess\ObjectAccess;
use Apie\ObjectAccessNormalizer\ObjectAccess\ObjectAccessSupportedInterface;
use Apie\Tests\ObjectAccessNormalizer\Mocks\SumExample;
use ReflectionClass;

class MockObjectAccessForSumExample extends ObjectAccess implements ObjectAccessSupportedInterface
{
    public function __construct()
    {
        parent::__construct(false);
    }

    public function isSupported(ReflectionClass $reflectionClass): bool
    {
        return $reflectionClass->name === SumExample::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getGetterFields(ReflectionClass $reflectionClass): array
    {
        return [1, 2, '+'];
    }

    /**
     * {@inheritDoc}
     */
    public function getGetterTypes(ReflectionClass $reflectionClass, string $fieldName): array
    {
        switch ($fieldName) {
            case 1:
                return parent::getGetterTypes($reflectionClass, 'one');
            case 2:
                return parent::getGetterTypes($reflectionClass, 'two');
            case '+':
                return parent::getGetterTypes($reflectionClass, 'addition');
        }
        return parent::getGetterTypes($reflectionClass, $fieldName);
    }

    public function getValue(object $instance, string $fieldName)
    {
        switch ($fieldName) {
            case 1:
                return parent::getValue($instance, 'one');
            case 2:
                return parent::getValue($instance, 'two');
            case '+':
                return parent::getValue($instance, 'addition');
        }
        return parent::getValue($instance, $fieldName);
    }

    public function getDescription(ReflectionClass $reflectionClass, string $fieldName, bool $preferGetters): ?string
    {
        switch ($fieldName) {
            case 1:
                return 'first number';
            case 2:
                return 'second number';
            case '+':
                return 'addition of first and second number';
        }
        return null;
    }
}
