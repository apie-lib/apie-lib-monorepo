<?php
namespace Apie\HtmlBuilders\Components\Layout;

use Apie\Core\BoundedContext\BoundedContextHashmap;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Lists\ReflectionMethodList;
use Apie\HtmlBuilders\Components\BaseComponent;
use Apie\HtmlBuilders\Lists\ComponentHashmap;

class TopBar extends BaseComponent
{
    public function __construct(
        ReflectionMethodList $authenticationMethods,
        bool $shouldDisplayBoundedContextSelect,
        BoundedContextId $boundedContextId,
        BoundedContextHashmap $boundedContextHashmap
    ) {
        parent::__construct(
            [
                'shouldDisplayBoundedContextSelect' => $shouldDisplayBoundedContextSelect,
                'selectedBoundedContextId' => $boundedContextId->toNative(),
                'boundedContextHashmap' => $boundedContextHashmap,
            ],
            new ComponentHashmap([
            ])
        );
    }
}