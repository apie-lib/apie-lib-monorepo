<?php
namespace Apie\Fixtures\Dto;

use Apie\CommonValueObjects\Enums\Gender;
use Apie\Core\Dto\DtoInterface;

class NullableExampleDto implements DtoInterface
{
    public ?string $nullableString;

    public ?int $nullableInteger;

    public ?float $nullableFloatingPoint;

    public ?bool $nullableTrueOrFalse;

    public ?Gender $nullableGender;
}
