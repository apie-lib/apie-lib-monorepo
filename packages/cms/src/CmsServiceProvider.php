<?php
namespace Apie\Cms;

use Apie\ServiceProviderGenerator\UseGeneratedMethods;
use Illuminate\Support\ServiceProvider;

/**
 * This file is generated with apie/service-provider-generator from file: cms.yaml
 * @codecoverageIgnore
 * @phpstan-ignore
 */
class CmsServiceProvider extends ServiceProvider
{
    use UseGeneratedMethods;

    public function register()
    {
        $this->app->singleton(
            \Apie\Cms\RouteDefinitions\CmsRouteDefinitionProvider::class,
            function ($app) {
                return new \Apie\Cms\RouteDefinitions\CmsRouteDefinitionProvider(
                
                );
            }
        );
        $this->app->tag([\Apie\Cms\RouteDefinitions\CmsRouteDefinitionProvider::class], 'apie.core.route_definition');
        $this->app->singleton(
            \Apie\Cms\Controllers\DashboardController::class,
            function ($app) {
                return new \Apie\Cms\Controllers\DashboardController(
                    $app->make(\Apie\HtmlBuilders\Factories\ComponentFactory::class),
                    $app->make(\Apie\Core\ContextBuilders\ContextBuilderFactory::class),
                    $app->make(\Apie\HtmlBuilders\Interfaces\ComponentRendererInterface::class),
                    $app->make(\Apie\ApieBundle\Wrappers\DashboardContents::class)
                );
            }
        );
        $this->app->tag([\Apie\Cms\Controllers\DashboardController::class], 'controller.service_arguments');
        $this->app->bind(\Apie\Cms\EmptyDashboard::class, 'apie.cms.dashboard_content');
        
        $this->app->singleton(
            \Apie\Cms\EmptyDashboard::class,
            function ($app) {
                return new \Apie\Cms\EmptyDashboard(
                
                );
            }
        );
        $this->app->singleton(
            \Apie\Cms\Controllers\GetResourceListController::class,
            function ($app) {
                return new \Apie\Cms\Controllers\GetResourceListController(
                    $app->make(\Apie\Common\ApieFacade::class),
                    $app->make(\Apie\HtmlBuilders\Factories\ComponentFactory::class),
                    $app->make(\Apie\Core\ContextBuilders\ContextBuilderFactory::class),
                    $app->make(\Apie\HtmlBuilders\Interfaces\ComponentRendererInterface::class)
                );
            }
        );
        $this->app->tag([\Apie\Cms\Controllers\GetResourceListController::class], 'controller.service_arguments');
        $this->app->singleton(
            \Apie\Cms\Controllers\RunGlobalMethodFormController::class,
            function ($app) {
                return new \Apie\Cms\Controllers\RunGlobalMethodFormController(
                    $app->make(\Apie\Common\ApieFacade::class),
                    $app->make(\Apie\HtmlBuilders\Factories\ComponentFactory::class),
                    $app->make(\Apie\Core\ContextBuilders\ContextBuilderFactory::class),
                    $app->make(\Apie\HtmlBuilders\Interfaces\ComponentRendererInterface::class)
                );
            }
        );
        $this->app->tag([\Apie\Cms\Controllers\RunGlobalMethodFormController::class], 'controller.service_arguments');
        $this->app->singleton(
            \Apie\Cms\Controllers\CreateResourceFormController::class,
            function ($app) {
                return new \Apie\Cms\Controllers\CreateResourceFormController(
                    $app->make(\Apie\Common\ApieFacade::class),
                    $app->make(\Apie\HtmlBuilders\Factories\ComponentFactory::class),
                    $app->make(\Apie\Core\ContextBuilders\ContextBuilderFactory::class),
                    $app->make(\Apie\HtmlBuilders\Interfaces\ComponentRendererInterface::class)
                );
            }
        );
        $this->app->tag([\Apie\Cms\Controllers\CreateResourceFormController::class], 'controller.service_arguments');
        $this->app->singleton(
            \Apie\Cms\Controllers\ModifyResourceFormController::class,
            function ($app) {
                return new \Apie\Cms\Controllers\ModifyResourceFormController(
                    $app->make(\Apie\Common\ApieFacade::class),
                    $app->make(\Apie\HtmlBuilders\Factories\ComponentFactory::class),
                    $app->make(\Apie\Core\ContextBuilders\ContextBuilderFactory::class),
                    $app->make(\Apie\HtmlBuilders\Interfaces\ComponentRendererInterface::class)
                );
            }
        );
        $this->app->tag([\Apie\Cms\Controllers\ModifyResourceFormController::class], 'controller.service_arguments');
        $this->app->singleton(
            \Apie\Cms\Controllers\FormCommitController::class,
            function ($app) {
                return new \Apie\Cms\Controllers\FormCommitController(
                    $app->make(\Apie\Core\ContextBuilders\ContextBuilderFactory::class),
                    $app->make(\Apie\Common\ApieFacade::class),
                    $app->make(\Apie\HtmlBuilders\Configuration\ApplicationConfiguration::class),
                    $app->make(\Apie\Core\BoundedContext\BoundedContextHashmap::class)
                );
            }
        );
        $this->app->tag([\Apie\Cms\Controllers\FormCommitController::class], 'controller.service_arguments');
        $this->app->singleton(
            'cms.layout.graphite_design_system',
            function ($app) {
                return \Apie\CmsLayoutGraphite\GraphiteDesignSystemLayout::createRenderer(
                
                );
                
            }
        );
        
    }
}