<?php
namespace Apie\HtmlBuilders\Components\Layout;

use Apie\Core\BoundedContext\BoundedContextHashmap;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Lists\ReflectionMethodList;
use Apie\HtmlBuilders\Components\BaseComponent;
use Apie\HtmlBuilders\Configuration\CurrentConfiguration;
use Apie\HtmlBuilders\Lists\ComponentHashmap;

class Logo extends BaseComponent
{
    public function __construct()
    {
        parent::__construct(
            []
        );
    }
}