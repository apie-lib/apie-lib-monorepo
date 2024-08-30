<?php
namespace Apie\Tests\TwigTemplateLayoutRenderer\Fixtures;

use Apie\HtmlBuilders\Components\BaseComponent;
use Apie\HtmlBuilders\Interfaces\ComponentInterface;
use Apie\HtmlBuilders\Lists\ComponentHashmap;

class Dummy extends BaseComponent
{
    public function __construct(string $name, ?ComponentInterface $child = null)
    {
        parent::__construct(
            [
                'name' => $name,
                'childExists' => $child !== null,
            ],
            $child ? new ComponentHashmap([
                'child' => $child,
            ]) : null
        );
    }
}
