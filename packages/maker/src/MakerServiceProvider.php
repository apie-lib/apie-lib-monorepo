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
        $this->app->singleton(
            \Apie\Maker\BoundedContext\Services\CodeWriter::class,
            function ($app) {
                return new \Apie\Maker\BoundedContext\Services\CodeWriter(
                    $app->make(\Apie\Core\Other\FileWriterInterface::class)
                );
            }
        );
        \Apie\ServiceProviderGenerator\TagMap::register(
            $this->app,
            \Apie\Maker\BoundedContext\Services\CodeWriter::class,
            array(
              0 => 'apie.context',
            )
        );
        $this->app->tag([\Apie\Maker\BoundedContext\Services\CodeWriter::class], 'apie.context');
        $this->app->singleton(
            \Apie\Maker\ContextBuilders\AddMakerConfigToContext::class,
            function ($app) {
                return new \Apie\Maker\ContextBuilders\AddMakerConfigToContext(
                    $this->parseArgument('%apie.maker%')
                );
            }
        );
        \Apie\ServiceProviderGenerator\TagMap::register(
            $this->app,
            \Apie\Maker\ContextBuilders\AddMakerConfigToContext::class,
            array(
              0 => 'apie.core.context_builder',
            )
        );
        $this->app->tag([\Apie\Maker\ContextBuilders\AddMakerConfigToContext::class], 'apie.core.context_builder');
        
    }
}
