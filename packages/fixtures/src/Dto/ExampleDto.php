<?php
namespace Apie\Fixtures\Dto;

use Apie\Core\Dto\DtoInterface;
use Apie\Fixtures\Enums\Gender;

class ExampleDto implements DtoInterface
{
    public string $string;

    public int $integer;

    public float $floatingPoint;

    public bool $trueOrFalse;

    public mixed $mixed;
    
    public $noType;

    public Gender $gender;
}
