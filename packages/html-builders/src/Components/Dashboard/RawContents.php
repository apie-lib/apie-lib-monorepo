<?php
namespace Apie\HtmlBuilders\Components\Dashboard;

use Apie\HtmlBuilders\Interfaces\ComponentInterface;
use Apie\HtmlBuilders\Lists\ComponentHashmap;

class RawContents implements ComponentInterface
{
    public function __construct(private array $attributes, private ComponentHashmap $parentComponents)
    {
    }

    public function getComponent(string $key): ComponentInterface
    {
        return $this->parentComponents[$key];
    }

    public function getAttribute(string $key): mixed
    {
        return $this->attributes[$key] ?? null;
    }
}