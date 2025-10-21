<?php
namespace Apie\Webdav;

use Apie\ServiceProviderGenerator\UseGeneratedMethods;
use Illuminate\Support\ServiceProvider;

/**
 * This file is generated with apie/service-provider-generator from file: webdav.yaml
 * @codeCoverageIgnore
 */
class WebdavServiceProvider extends ServiceProvider
{
    use UseGeneratedMethods;

    public function register()
    {
        $this->app->singleton(
            '_defaults',
            function ($app) {
                return new \_defaults(
                
                );
            }
        );
        $this->app->singleton(
            \Apie\Webdav\RouteDefinitions\WebdavRouteDefinitionProvider::class,
            function ($app) {
                return new \Apie\Webdav\RouteDefinitions\WebdavRouteDefinitionProvider(
                
                );
            }
        );
        \Apie\ServiceProviderGenerator\TagMap::register(
            $this->app,
            \Apie\Webdav\RouteDefinitions\WebdavRouteDefinitionProvider::class,
            array(
              0 =>
              array(
                'name' => 'apie.common.route_definition',
              ),
            )
        );
        $this->app->tag([\Apie\Webdav\RouteDefinitions\WebdavRouteDefinitionProvider::class], 'apie.common.route_definition');
        $this->app->singleton(
            \Apie\Webdav\Controller\WebdavController::class,
            function ($app) {
                return new \Apie\Webdav\Controller\WebdavController(
                    $app->make(\Apie\ApieFileSystem\ApieFilesystemFactory::class),
                    $app->make(\Apie\Core\ContextBuilders\ContextBuilderFactory::class)
                );
            }
        );
        \Apie\ServiceProviderGenerator\TagMap::register(
            $this->app,
            \Apie\Webdav\Controller\WebdavController::class,
            array(
              0 =>
              array(
                'name' => 'controller.service_arguments',
              ),
            )
        );
        $this->app->tag([\Apie\Webdav\Controller\WebdavController::class], 'controller.service_arguments');
        
    }
}
