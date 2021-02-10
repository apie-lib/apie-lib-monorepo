<?php


namespace Apie\TypeJuggling\Exceptions;

use Apie\Core\Exceptions\ApieException;
use Apie\Core\Exceptions\FieldNameAwareInterface;

/**
 * Error thrown if a value is missing in the array.
 */
class MissingValueException extends ApieException implements FieldNameAwareInterface
{
    private $fieldName;

    public function __construct(string $fieldName)
    {
        $this->fieldName = $fieldName;
        parent::__construct(422, 'Value missing for field name "' . $fieldName . '"');
    }

    /**
     * @return string
     */
    public function getFieldName(): string
    {
        return $this->fieldName;
    }
}
