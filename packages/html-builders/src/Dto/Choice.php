<?php
namespace Apie\HtmlBuilders\Dto;

use Apie\Core\Dto\DtoInterface;

class Choice implements DtoInterface
{
    public function __construct(public readonly mixed $value, public readonly string $display)
    {
    }
}
