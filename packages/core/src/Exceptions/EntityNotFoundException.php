<?php
namespace Apie\Core\Exceptions;

use Apie\Core\Identifiers\IdentifierInterface;

class EntityNotFoundException extends ApieException
{
    public function __construct(IdentifierInterface $identifier)
    {
        parent::__construct(
            sprintf(
                "Entity '%s' with id '%s' is not found!",
                $identifier::getReferenceFor()->getShortName(),
                $identifier->toNative()
            )
        );
    }
}
