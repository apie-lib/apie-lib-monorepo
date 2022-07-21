<?php
namespace Apie\Fixtures\Dto;

use Apie\Core\Dto\DtoInterface;
use Apie\Fixtures\Enums\Gender;

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
