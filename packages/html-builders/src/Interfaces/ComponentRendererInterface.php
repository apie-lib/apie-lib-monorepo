<?php
namespace Apie\HtmlBuilders\Interfaces;

use Apie\HtmlBuilders\Lists\ComponentHashmap;

interface ComponentRendererInterface
{
    public function render(ComponentInterface $componentInterface): string;
}