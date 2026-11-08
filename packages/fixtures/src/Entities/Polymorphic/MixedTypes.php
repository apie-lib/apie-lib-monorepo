<?php
namespace Apie\Fixtures\Entities\Polymorphic;

use Apie\Core\Entities\PolymorphicEntityInterface;

abstract class MixedTypes implements PolymorphicEntityInterface
{
    protected MixedTypesIdentifier $id;

    public function getId(): MixedTypesIdentifier
    {
        return $this->id;
    }
    public static function getDiscriminatorMapping(): \Apie\Core\Other\DiscriminatorMapping
    {
        return new \Apie\Core\Other\DiscriminatorMapping(
            'type',
            new \Apie\Core\Other\DiscriminatorConfig(
                'integer',
                IntegerType::class
            ),
            new \Apie\Core\Other\DiscriminatorConfig(
                'string',
                StringType::class
            ),
        );
    }
}
