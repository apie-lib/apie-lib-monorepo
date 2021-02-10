<?php


namespace Apie\TypeJuggling\Exceptions;

use Apie\Core\Exceptions\ApieException;
use Apie\Core\Exceptions\FieldNameAwareInterface;
use Apie\TypeJuggling\TypeUtilInterface;

/**
 * Thrown when a type could not be converted into the native type.
 */
class InvalidInputException extends ApieException implements FieldNameAwareInterface
{
    private $fieldName;

    public function __construct(string $fieldName, TypeUtilInterface $typeUtil, $input)
    {
        $this->fieldName = $fieldName;
        $type =  get_debug_type($input);
        if (is_array($input)) {
            $type = json_encode($input);
        }
        $message = 'Wrong input for field "' . $fieldName . '" expect '. $typeUtil . ' got ' . $type;
        parent::__construct(422, $message);
    }

    /**
     * @return string
     */
    public function getFieldName(): string
    {
        return $this->fieldName;
    }
}
