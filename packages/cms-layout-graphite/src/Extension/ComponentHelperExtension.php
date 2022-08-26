<?php
namespace Apie\CmsLayoutGraphite\Extension;

use Symfony\Component\DependencyInjection\Extension\AbstractExtension;

class ComponentHelperExtension extends AbstractExtension
{
    public function getFunctions() {
        return [
            new TwigFunction('component', [$this, 'component'], ['needs_environment' => true, 'needs_context' => true, 'is_safe' => ['all']]),
            new TwigFunction('attributes', [$this, 'component'], ['needs_environment' => true, 'needs_context' => true, 'is_safe' => ['all']])

        ];
    }
}