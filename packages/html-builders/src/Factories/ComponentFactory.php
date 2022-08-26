<?php
namespace Apie\HtmlBuilders\Factories;

use Apie\Core\BoundedContext\BoundedContext;
use Apie\Core\BoundedContext\BoundedContextHashmap;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Lists\ReflectionMethodList;
use Apie\HtmlBuilders\Components\Dashboard\RawContents;
use Apie\HtmlBuilders\Components\Layout;
use Apie\HtmlBuilders\Configuration\ApplicationConfiguration;
use Apie\HtmlBuilders\Interfaces\ComponentInterface;
use Apie\HtmlBuilders\Lists\ComponentHashmap;
use Stringable;

class ComponentFactory {
    public function __construct(
        private readonly ApplicationConfiguration $applicationConfiguration,
        private readonly BoundedContextHashmap $boundedContextHashmap
    ) {
    }

    public function createRawContents(Stringable|string $dashboardContents): ComponentInterface
    {
        return new RawContents($dashboardContents);
    }

    public function createWrapLayout(string $pageTitle, ?BoundedContextId $boundedContextId, ComponentInterface $contents): ComponentInterface
    {
        $authenticationMethods = new ReflectionMethodList();
        if ($boundedContextId && isset($boundedContextHashmap[$boundedContextId])) {
            // TODO $authenticationMethods = $boundedContextHashmap[$boundedContextId]
        }
        return new Layout(
            $this->applicationConfiguration->getBrowserTitle($pageTitle),
            $authenticationMethods,
            $this->applicationConfiguration->shouldDisplayBoundedContextSelect(),
            $contents
        );
    }
}