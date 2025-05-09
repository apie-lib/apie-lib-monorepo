<?php
namespace Apie\AiInstructor;

use Apie\ServiceProviderGenerator\UseGeneratedMethods;
use Illuminate\Support\ServiceProvider;

/**
 * This file is generated with apie/service-provider-generator from file: ai_instructor.yaml
 * @codeCoverageIgnore
 */
class AiInstructorServiceProvider extends ServiceProvider
{
    use UseGeneratedMethods;

    public function register()
    {
        $this->app->singleton(
            \Apie\AiInstructor\AiInstructor::class,
            function ($app) {
                return new \Apie\AiInstructor\AiInstructor(
                    $app->make(\Apie\SchemaGenerator\SchemaGenerator::class),
                    $app->make(\Apie\Serializer\Serializer::class),
                    $app->make(\Apie\AiInstructor\AiClient::class)
                );
            }
        );
        \Apie\ServiceProviderGenerator\TagMap::register(
            $this->app,
            \Apie\AiInstructor\AiInstructor::class,
            array(
              0 => 'apie.context',
            )
        );
        $this->app->tag([\Apie\AiInstructor\AiInstructor::class], 'apie.context');
        $this->app->singleton(
            \Apie\AiInstructor\AiClient::class,
            function ($app) {
                return \Apie\AiInstructor\AiClient::create(
                    $app->bound('http_client') ? $app->make('http_client') : null,
                    $this->parseArgument('%apie.ai.base_url%'),
                    $this->parseArgument('%apie.ai.api_key%')
                );
                
            }
        );
        \Apie\ServiceProviderGenerator\TagMap::register(
            $this->app,
            \Apie\AiInstructor\AiClient::class,
            array(
              0 => 'apie.context',
            )
        );
        $this->app->tag([\Apie\AiInstructor\AiClient::class], 'apie.context');
        $this->app->singleton(
            \Apie\AiInstructor\AiPlaygroundCommand::class,
            function ($app) {
                return new \Apie\AiInstructor\AiPlaygroundCommand(
                    $app->make(\Apie\AiInstructor\AiInstructor::class)
                );
            }
        );
        \Apie\ServiceProviderGenerator\TagMap::register(
            $this->app,
            \Apie\AiInstructor\AiPlaygroundCommand::class,
            array(
              0 =>
              array(
                'name' => 'console.command',
              ),
            )
        );
        $this->app->tag([\Apie\AiInstructor\AiPlaygroundCommand::class], 'console.command');
        
    }
}
