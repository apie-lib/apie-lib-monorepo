<?php

namespace Apie\IntegrationTests\Apie\TypeDemo\Resources;

use Apie\Core\Entities\EntityInterface;
use Apie\CountryAndPhoneNumber\BelgianPhoneNumber;
use Apie\CountryAndPhoneNumber\DutchPhoneNumber;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\UnionObjectId;

class UnionObject implements EntityInterface
{
    private UnionObjectId $id;

    public function __construct(
        public string|int $value,
        public float|bool $otherValue,
        public DutchPhoneNumber|BelgianPhoneNumber|null $phoneNumber = null,
        ?UnionObjectId $id = null,
    ) {
        $this->id = $id ?? UnionObjectId::createRandom();
    }

    public function getId(): UnionObjectId
    {
        return $this->id;
    }
}
