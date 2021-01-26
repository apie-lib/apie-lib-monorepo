<?php


namespace Apie\ObjectAccessNormalizer\Interfaces;

interface PriorityAwareInterface
{
    /**
     * Returns priority of this getter/setter. A higher priority results in being picked over an other getter/setter.
     *
     * @return int
     */
    public function getPriority(): int;
}
