<?php
namespace Apie\HtmlBuilders\Interfaces;

use Apie\HtmlBuilders\Lists\ComponentHashmap;

interface ComponentInterface
{
    public function __construct(array $attributes, ComponentHashmap $parentComponents);

    public function getComponent(string $key): ComponentInterface;

    public function getAttribute(string $key): mixed;
}