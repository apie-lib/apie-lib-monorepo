<?php
namespace Apie\Core\Exceptions;

/**
 * Exception thrown if no ApiResource annotation is found on the class docblock.
 */
class ApiResourceAnnotationNotFoundException extends ApieException
{
    public function __construct($classNameOrInstance)
    {
        $className = gettype($classNameOrInstance) === 'object' ? get_class($classNameOrInstance) : $classNameOrInstance;
        parent::__construct(500, 'Class ' . $className . ' has no ApiResource annotation.');
    }
}
