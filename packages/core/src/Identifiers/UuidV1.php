<?php
namespace Apie\Core\Identifiers;

use Apie\Core\Attributes\FakeMethod;
use Ramsey\Uuid\Uuid as RamseyUuid;

#[FakeMethod("createRandom")]
class UuidV1 extends Uuid
{
    public static function createRandom(): self
    {
        return new static(RamseyUuid::uuid1()->toString());
    }
}
