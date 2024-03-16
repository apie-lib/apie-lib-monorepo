<?php

namespace App\ApiePlayground\Types\Resources;

use Apie\Core\Attributes\FakeCount;
use Apie\DateValueObjects\Ranges\DateTimeRange;
use App\ApiePlayground\Types\Identifiers\DateFieldsIdentifier;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

#[FakeCount(25)]
class DateFields implements \Apie\Core\Entities\EntityInterface
{
    private DateFieldsIdentifier $id;

    public DateTimeInterface $interface;

    public DateTimeImmutable $immutable;

    public DateTime $mutable;

    public DateTimeRange $range;

    public ?DateTimeInterface $nullableInterface;

    public ?DateTimeImmutable $nullableImmutable;

    public ?DateTime $nullableMutable;

    public function __construct()
    {
        $this->id = DateFieldsIdentifier::createRandom();
    }

    public function getId(): DateFieldsIdentifier
    {
        return $this->id;
    }
}
