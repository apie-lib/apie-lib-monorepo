<?php
namespace Apie\HtmlBuilders\Interfaces;

use Apie\HtmlBuilders\Lists\ComponentHashmap;

interface ComponentInterface
{
    public function getComponent(string $key): ComponentInterface;

    public function getAttribute(string $key): mixed;
}