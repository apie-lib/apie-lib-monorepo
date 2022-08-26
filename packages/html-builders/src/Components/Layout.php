<?php
namespace Apie\HtmlBuilders\Components;

use Apie\Core\BoundedContext\BoundedContextHashmap;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Lists\ReflectionMethodList;
use Apie\HtmlBuilders\Components\Dashboard\RawContents;
use Apie\HtmlBuilders\Components\Layout\TopBar;
use Apie\HtmlBuilders\Interfaces\ComponentInterface;
use Apie\HtmlBuilders\Lists\ComponentHashmap;

class Layout extends BaseComponent
{
    public function __construct(
        string $browserTitle,
        ReflectionMethodList $authenticationMethods,
        bool $shouldDisplayBoundedContextSelect,
        BoundedContextId $boundedContextId,
        BoundedContextHashmap $boundedContextHashmap,
        ComponentInterface $contents
    ) {
        parent::__construct(
            [
                'title' => $browserTitle,
            ],
            new ComponentHashmap([
                'top' => new TopBar($authenticationMethods, $shouldDisplayBoundedContextSelect, $boundedContextId, $boundedContextHashmap),
                'menu' => new RawContents(''),
                'content' => $contents,
            ])
        );
    }
}