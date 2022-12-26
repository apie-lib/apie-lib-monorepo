<?php
namespace Apie\CmsApiDropdownOption\Dtos;

use Apie\Core\Dto\DtoInterface;

final class DropdownOption implements DtoInterface
{
    public string $value;

    public string $displayValue;
}