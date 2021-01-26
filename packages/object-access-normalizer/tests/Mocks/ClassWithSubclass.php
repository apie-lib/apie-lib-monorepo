<?php


namespace Apie\Tests\ObjectAccessNormalizer\Mocks;

/**
 * Class for ApieObjectAccessNormalizerTest with recursion and validation errors in subclasses.
 */
class ClassWithSubclass
{
    /**
     * @var ClassWithoutConstructorTypehint
     */
    private $subClass;

    public function __construct(ClassWithoutConstructorTypehint $subClass)
    {
        $this->subClass = $subClass;
    }

    public function getSubClass(): ClassWithoutConstructorTypehint
    {
        return $this->subClass;
    }

    public function setSubClass(ClassWithoutConstructorTypehint $subClass): self
    {
        $this->subClass = $subClass;
        return $this;
    }
}
