<?php

namespace Apie\CommonApie;

/**
 * Helper class to make a general Apie instance with common plugins active.
 */
class DefaultApie
{
    public static function createDefaultApie(bool $debug = false, array $additionalPlugins = [], ?string $cacheFolder = null, bool $defaultResources = true): Apie
    {
        $plugins = $additionalPlugins;
        $plugins[] = class_exists(CarbonPlugin::class) ? new CarbonPlugin() : new DateTimePlugin();
        $plugins[] = new PaginationPlugin();
        $plugins[] = new UuidPlugin();
        $plugins[] = new ValueObjectPlugin();
        if ($defaultResources) {
            $plugins[] = new ApplicationInfoPlugin();
            $plugins[] = new StatusCheckPlugin([]);
        }
        return new Apie($plugins, $debug, $cacheFolder);
    }
}
