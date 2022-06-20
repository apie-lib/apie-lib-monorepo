<?php
namespace Apie\Fixtures\Dto;

use Apie\CommonValueObjects\Enums\Gender;
use Apie\Core\Dto\DtoInterface;

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
