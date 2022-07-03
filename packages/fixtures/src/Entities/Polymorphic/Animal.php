<?php
namespace Apie\Fixtures\Entities\Polymorphic;

use Apie\Core\Entities\PolymorphicEntityInterface;
use Apie\Core\Other\DiscriminatorConfig;
use Apie\Core\Other\DiscriminatorMapping;

abstract class Animal implements PolymorphicEntityInterface
{
    private AnimalIdentifier $id;

    public function __construct(AnimalIdentifier $id = null)
    {
        $this->id = $id ?? AnimalIdentifier::createRandom();
    }

    public function getId(): AnimalIdentifier
    {
        return $this->id;
    }

    final public static function getDiscriminatorMapping(): DiscriminatorMapping
    {
        return new DiscriminatorMapping(
            new DiscriminatorConfig('cow', Cow::class),
            new DiscriminatorConfig('elephant', Elephant::class),
            new DiscriminatorConfig('fish', Fish::class)
        );
    }
}
