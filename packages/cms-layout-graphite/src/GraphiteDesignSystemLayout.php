<?php
namespace Apie\CmsLayoutGraphite;

/**
 * This is a stub class
 */
final class GraphiteDesignSystemLayout
{
    private function __construct()
    {
    }

    public static function createRenderer(): TwigRenderer
    {
        return new TwigRenderer(__DIR__ . '/resources');
    }
}