<?php
namespace Apie\ExchangeRate;

use Apie\ServiceProviderGenerator\UseGeneratedMethods;
use Illuminate\Support\ServiceProvider;

/**
 * This file is generated with apie/service-provider-generator from file: exchangerate.yaml
 * @codeCoverageIgnore
 */
class ExchangeRateServiceProvider extends ServiceProvider
{
    use UseGeneratedMethods;

    public function register()
    {
        $this->registerSingleton(
            \Apie\ExchangeRate\ExchangeRateService::class,
            function ($app) {
                return new \Apie\ExchangeRate\ExchangeRateService(
                    $app->bound('http_client') ? $app->make('http_client') : null
                );
            }
        );
        
    }
}
