<?php

namespace App\Providers;

use Apie\CmsLayoutIonic\IonicDesignSystemLayout;
use Apie\HtmlBuilders\Assets\AssetManager;
use Apie\HtmlBuilders\Interfaces\ComponentRendererInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(ComponentRendererInterface::class, function () {
             return IonicDesignSystemLayout::createRenderer($this->app->get(AssetManager::class));
        });
    }
}