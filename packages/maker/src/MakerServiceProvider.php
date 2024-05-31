<?php
namespace Apie\Maker;

use Apie\ServiceProviderGenerator\UseGeneratedMethods;
use Illuminate\Support\ServiceProvider;

/**
 * This file is generated with apie/service-provider-generator from file: maker.yaml
 * @codeCoverageIgnore
 */
class MakerServiceProvider extends ServiceProvider
{
    use UseGeneratedMethods;

    public function register()
    {
        $this->app->singleton(
            \Apie\Maker\CodeGenerators\CreateDomainObject::class,
            function ($app) {
                return new \Apie\Maker\CodeGenerators\CreateDomainObject(
                    $this->parseArgument('%apie.bounded_contexts%')
                );
            }
        );
        $this->app->singleton(
            \Apie\Maker\Command\ApieCreateDomainCommand::class,
            function ($app) {
                return new \Apie\Maker\Command\ApieCreateDomainCommand(
                    $app->make(\Apie\Core\BoundedContext\BoundedContextHashmap::class),
                    $app->make(\Apie\Maker\CodeGenerators\CreateDomainObject::class),
                    $app->make(\Apie\Core\Other\FileWriterInterface::class)
                );
            }
        );
        \Apie\ServiceProviderGenerator\TagMap::register(
            $this->app,
            \Apie\Maker\Command\ApieCreateDomainCommand::class,
            array(
              0 =>
              array(
                'name' => 'console.command',
              ),
            )
        );
        $this->app->tag([\Apie\Maker\Command\ApieCreateDomainCommand::class], 'console.command');
    }
}
