<?php
namespace Apie\HtmlBuilders\Components\Layout;

use Apie\Core\BoundedContext\BoundedContextHashmap;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Lists\ReflectionMethodList;
use Apie\HtmlBuilders\Components\BaseComponent;
use Apie\HtmlBuilders\Configuration\CurrentConfiguration;
use Apie\HtmlBuilders\Lists\ComponentHashmap;

class BoundedContextSelect extends BaseComponent
{
    public function __construct(
        CurrentConfiguration $currentConfiguration
    ) {
        $contextId = $currentConfiguration->getSelectedBoundedContextId();
        parent::__construct(
            [
                'selectedBoundedContextId' => $contextId ? $contextId->toNative() : null,
                'boundedContextHashmap' => $currentConfiguration->getBoundedContextHashmap(),
            ],
            new ComponentHashmap([
            ])
        );
    }
}