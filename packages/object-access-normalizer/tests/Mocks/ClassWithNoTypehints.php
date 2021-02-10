<?php


namespace Apie\Tests\ObjectAccessNormalizer\Mocks;

class ClassWithNoTypehints
{
    public $alsoNoTypehint;

    private $noTypehint;

    public function __construct($noTypehint)
    {
        $this->noTypehint = $noTypehint;
    }

    public function getNoTypehint()
    {
        return $this->noTypehint;
    }

    public function setNoTypehint($noTypehint): self
    {
        $this->noTypehint = $noTypehint;
        return $this;
    }
}
