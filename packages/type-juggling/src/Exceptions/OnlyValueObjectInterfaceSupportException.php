<?php


namespace Apie\TypeJuggling\Exceptions;

use Apie\Core\Exceptions\ApieException;
use Apie\Core\Exceptions\FieldNameAwareInterface;

/**
 * Exception thrown if a wrong type is sent to fromNative that is not supported, for
 * example resources or any arbitrary class.
 */
class OnlyValueObjectInterfaceSupportException extends ApieException implements FieldNameAwareInterface
{
    private $fieldName;

    public function __construct(string $fieldName, ?string $className)
    {
        $this->fieldName = $fieldName;
        $message = 'Typehint object on field "' . $fieldName . '" is not supported';
        if ($className !== null) {
            $message = 'Class ' . $className . ' found on field "' . $fieldName . '" is not implementing ValueObjectInterface and is not supported';
        }
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
