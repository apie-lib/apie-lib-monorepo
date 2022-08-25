<?php
namespace Apie\HtmlBuilders\Factories;

use Apie\HtmlBuilders\Components\Dashboard\RawContents;
use Apie\HtmlBuilders\Components\Layout;
use Apie\HtmlBuilders\Interfaces\ComponentInterface;
use Apie\HtmlBuilders\Lists\ComponentHashmap;
use Stringable;

class ComponentFactory {
    public function __construct(private Stringable|string $dashboardContents)
    {
    }

    public function createDashboard(): ComponentInterface
    {
        return new Layout([], new ComponentHashmap([
            'contents' => new RawContents(['html' => $this->dashboardContents], new ComponentHashmap())
        ]));
    }
}