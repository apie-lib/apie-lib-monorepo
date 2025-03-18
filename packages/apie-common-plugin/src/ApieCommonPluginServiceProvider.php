<?php
namespace Apie\ApieCommonPlugin;

class ApieCommonPluginServiceProvider extends GeneratedApieCommonPluginServiceProvider
{
    public function register(): void
    {
        if (!class_exists(AvailableApieObjectProvider::class)) {
            return;
        }
        foreach (AvailableApieObjectProvider::getAvailableServices() as $serviceClass) {
            $this->app->bind($serviceClass);
            // TODO: tagmap
        }
    }
}
