<?php
namespace Apie\Faker;

use Apie\ServiceProviderGenerator\UseGeneratedMethods;
use Illuminate\Support\ServiceProvider;

/**
 * This file is generated with apie/service-provider-generator from file: faker.yaml
 * @codecoverageIgnore
 * @phpstan-ignore
 */
class FakerServiceProvider extends ServiceProvider
{
    use UseGeneratedMethods;

    public function register()
    {
        $this->app->singleton(
            \Faker\Generator::class,
            function ($app) {
                return call_user_func(
                    'Apie\\ApieBundle\\Wrappers\\GeneralServiceFactory::createFaker',
                    $this->getTaggedServicesIterator('apie.faker')
                );
                
            }
        );
        $this->app->singleton(
            \Apie\Faker\Datalayers\FakerDatalayer::class,
            function ($app) {
                return new \Apie\Faker\Datalayers\FakerDatalayer(
                    $app->make(\Faker\Generator::class)
                );
            }
        );
        $this->app->tag([\Apie\Faker\Datalayers\FakerDatalayer::class], 'apie.datalayer');
        $this->app->bind(\Faker\Generator::class, 'apie.faker');
        
        
    }
}