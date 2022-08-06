<?php
namespace Apie\Core\Entities;

use Apie\Core\Identifiers\IdentifierInterface;

interface EntityInterface
{
    /**
     * @return IdentifierInterface<static>
     */
    public function getId(): IdentifierInterface;
}
