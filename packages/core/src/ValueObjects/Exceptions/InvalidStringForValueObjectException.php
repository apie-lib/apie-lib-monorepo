<?php
namespace Apie\Core\ValueObjects\Exceptions;

use Apie\Core\Exceptions\ApieException;
use Apie\Core\ValueObjects\Utils;

class InvalidStringForValueObjectException extends ApieException
{
    public function __construct(string $input, ValueObjectInterface $valueObject) {
        parent::__construct(
            sprintf(
                'Value "%s" is not valid for value object of type: %s',
                $input,
                Utils::getDisplayNameForValueObject($valueObject)
            )
        )
    }
}