<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Entities;

use Apie\Core\Other\DiscriminatorConfig;
use Apie\Core\Other\DiscriminatorMapping;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\Animal;

abstract class Bird extends Animal
{
    public function isCapableOfFlying(): bool
    {
        return true;
    }

    public static function getDiscriminatorMapping(): DiscriminatorMapping
    {
        return new DiscriminatorMapping(
            'name',
            new DiscriminatorConfig('ostrich', Ostrich::class),
            new DiscriminatorConfig('seagull', Seagull::class),
        );
    }
}
