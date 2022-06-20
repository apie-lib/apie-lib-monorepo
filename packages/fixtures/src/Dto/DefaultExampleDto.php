<?php
namespace Apie\Fixtures\Dto;

use Apie\CommonValueObjects\Enums\Gender;
use Apie\Core\Dto\DtoInterface;

class DefaultExampleDto implements DtoInterface
{
    public string $string = 'default value';

    public int $integer = 42;

    public float $floatingPoint = 1.5;

    public bool $trueOrFalse = true;

    public mixed $mixed = '48';
    
    public $noType = 'Boom';

    public Gender $gender = Gender::MALE;
}
