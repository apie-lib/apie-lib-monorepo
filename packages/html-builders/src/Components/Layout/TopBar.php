<?php
namespace APie\HtmlBuilders\Components\Layout;

use Apie\Core\Lists\ReflectionMethodList;
use Apie\HtmlBuilders\Components\BaseComponent;
use Apie\HtmlBuilders\Interfaces\ComponentInterface;
use Apie\HtmlBuilders\Lists\ComponentHashmap;

class TopBar extends BaseComponent
{
    public function __construct(ReflectionMethodList $authenticationMethods, bool $shouldDisplayBoundedContextSelect)
    {
        parent::__construct(
            [],
            new ComponentHashmap([
            ])
        );
    }
}