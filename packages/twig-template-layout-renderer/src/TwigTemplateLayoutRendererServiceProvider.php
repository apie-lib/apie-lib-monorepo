<?php
namespace Apie\TwigTemplateLayoutRenderer;

use Apie\ServiceProviderGenerator\UseGeneratedMethods;
use Illuminate\Support\ServiceProvider;

/**
 * This file is generated with apie/service-provider-generator from file: twig_template_layout_renderer.yaml
 * @codeCoverageIgnore
 */
class TwigTemplateLayoutRendererServiceProvider extends ServiceProvider
{
    use UseGeneratedMethods;

    public function register()
    {
        $this->app->singleton(
            \Apie\TwigTemplateLayoutRenderer\Command\CreateCustomLayoutRendererCommand::class,
            function ($app) {
                return new \Apie\TwigTemplateLayoutRenderer\Command\CreateCustomLayoutRendererCommand(
                    $app->make(\Apie\Core\Other\FileWriterInterface::class),
                    $app->make(\Apie\TwigTemplateLayoutRenderer\Skeleton\ClassCodeGenerator::class)
                );
            }
        );
        \Apie\ServiceProviderGenerator\TagMap::register(
            $this->app,
            \Apie\TwigTemplateLayoutRenderer\Command\CreateCustomLayoutRendererCommand::class,
            array(
              0 =>
              array(
                'name' => 'console.command',
              ),
            )
        );
        $this->app->tag([\Apie\TwigTemplateLayoutRenderer\Command\CreateCustomLayoutRendererCommand::class], 'console.command');
        $this->app->singleton(
            \Apie\TwigTemplateLayoutRenderer\Skeleton\ClassCodeGenerator::class,
            function ($app) {
                return new \Apie\TwigTemplateLayoutRenderer\Skeleton\ClassCodeGenerator(
                
                );
            }
        );
        
    }
}
