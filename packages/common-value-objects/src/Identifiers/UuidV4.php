<?php
namespace Apie\CommonValueObjects\Identifiers;

use Apie\Core\Attributes\FakeMethod;
use Ramsey\Uuid\Uuid as RamseyUuid;

#[FakeMethod("createRandom")]
class UuidV4 extends Uuid
{
    public static function createRandom(): self
    {
        return new self(RamseyUuid::uuid4()->toString());
    }
}
