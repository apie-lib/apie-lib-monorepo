<?php


namespace Apie\ValueObjects\Exceptions;

use Apie\Core\Exceptions\ApieException;
use Apie\Core\Exceptions\LocalizationableException;
use Apie\Core\Exceptions\LocalizationInfo;
use ReflectionClass;

/**
 * Exceptions thrown by value Apie value objects to indicate the value is not correct for the value object.
 */
class InvalidValueForValueObjectException extends ApieException implements LocalizationableException
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var mixed
     */
    private $value;

    public function __construct($value, $valueObject)
    {
        $refl = new ReflectionClass($valueObject);
        $this->name = strtolower((string) preg_replace('/(?<!^)[A-Z]/', '_$0', $refl->getShortName()));
        $this->value = $value;
        parent::__construct(
            422,
            '"' . $value . '" is not a valid value for value object ' . $this->name
        );
    }

    public function getI18n(): LocalizationInfo
    {
        return new LocalizationInfo(
            'validation.format',
            [
                'name' => $this->name,
                'value' => $this->value,
            ]
        );
    }
}