<?php
namespace Apie\Fixtures\Dto;

use Apie\Core\Dto\DtoInterface;
use Apie\Fixtures\Enums\Gender;

class NullableExampleDto implements DtoInterface
{
    public ?string $nullableString;

    public ?int $nullableInteger;

    public ?float $nullableFloatingPoint;

    public ?bool $nullableTrueOrFalse;

    public ?Gender $nullableGender;
}
