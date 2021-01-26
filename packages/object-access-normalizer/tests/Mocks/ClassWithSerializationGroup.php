<?php


namespace Apie\Tests\ObjectAccessNormalizer\Mocks;

use Symfony\Component\Serializer\Annotation\Groups;

class ClassWithSerializationGroup
{
    /**
     * @var ClassWithSerializationGroup|null
     * @Groups({"missing"})
     */
    public $value1;

    /**
     * @var ClassWithNoTypehints[]
     * @Groups({"missing"})
     */
    public $value2 = [];

    /**
     * @var ClassWithNoTypehints
     */
    public $value3;
}
