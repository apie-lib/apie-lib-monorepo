<?php

namespace Apie\Tests\ObjectAccessNormalizer\Mocks;

class RecursiveObject
{
    /**
     * @var RecursiveObject
     */
    private $child;

    public function setChild(?RecursiveObject $child): self
    {
        $this->child = $child;
        return $this;
    }

    public function getChild(): ?RecursiveObject
    {
        return $this->child;
    }
}
