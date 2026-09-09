<?php
namespace Apie\ApieFileSystem;

use Apie\ServiceProviderGenerator\UseGeneratedMethods;
use Illuminate\Support\ServiceProvider;

/**
 * This file is generated with apie/service-provider-generator from file: apie_file_system.yaml
 * @codeCoverageIgnore
 */
class ApieFileSystemServiceProvider extends ServiceProvider
{
    use UseGeneratedMethods;

    public function register()
    {
        $this->registerSingleton(
            \Apie\ApieFileSystem\ApieFilesystemFactory::class,
            function ($app) {
                return new \Apie\ApieFileSystem\ApieFilesystemFactory(
                    actionDefinitionProvider: $app->make(\Apie\Common\ActionDefinitionProvider::class),
                    boundedContextHashmap: $app->make(\Apie\Core\BoundedContext\BoundedContextHashmap::class)
                );
            }
        );
        
    }
}
