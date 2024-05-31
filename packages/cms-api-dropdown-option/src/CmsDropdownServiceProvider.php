<?php
namespace Apie\CmsApiDropdownOption;

use Apie\ServiceProviderGenerator\UseGeneratedMethods;
use Illuminate\Support\ServiceProvider;

/**
 * This file is generated with apie/service-provider-generator from file: cms_dropdown.yaml
 * @codeCoverageIgnore
 */
class CmsDropdownServiceProvider extends ServiceProvider
{
    use UseGeneratedMethods;

    public function register()
    {
        $this->app->singleton(
            \Apie\CmsApiDropdownOption\Controllers\DropdownOptionController::class,
            function ($app) {
                return new \Apie\CmsApiDropdownOption\Controllers\DropdownOptionController(
                    $app->make(\Apie\Core\ContextBuilders\ContextBuilderFactory::class),
                    $app->make(\Apie\Common\ApieFacade::class),
                    $app->make(\Apie\Serializer\EncoderHashmap::class)
                );
            }
        );
        \Apie\ServiceProviderGenerator\TagMap::register(
            $this->app,
            \Apie\CmsApiDropdownOption\Controllers\DropdownOptionController::class,
            array(
              0 => 'controller.service_arguments',
            )
        );
        $this->app->tag([\Apie\CmsApiDropdownOption\Controllers\DropdownOptionController::class], 'controller.service_arguments');
        $this->app->singleton(
            \Apie\CmsApiDropdownOption\DropdownOptionProvider\DropdownOptionProviderInterface::class,
            function ($app) {
                return \Apie\CmsApiDropdownOption\DropdownOptionProvider\ChainedDropdownOptionProvider::create(
                    $this->getTaggedServicesIterator(\Apie\CmsApiDropdownOption\DropdownOptionProvider\DropdownOptionProviderInterface::class)
                );
            }
        );
        \Apie\ServiceProviderGenerator\TagMap::register(
            $this->app,
            \Apie\CmsApiDropdownOption\DropdownOptionProvider\DropdownOptionProviderInterface::class,
            array(
              0 => 'apie.context',
            )
        );
        $this->app->tag([\Apie\CmsApiDropdownOption\DropdownOptionProvider\DropdownOptionProviderInterface::class], 'apie.context');
        $this->app->singleton(
            \Apie\CmsApiDropdownOption\DropdownOptionProvider\EntityIdentifierOptionProvider::class,
            function ($app) {
                return new \Apie\CmsApiDropdownOption\DropdownOptionProvider\EntityIdentifierOptionProvider(
                    $app->make(\Apie\Core\BoundedContext\BoundedContextHashmap::class),
                    $app->make(\Apie\Core\Datalayers\ApieDatalayer::class)
                );
            }
        );
        \Apie\ServiceProviderGenerator\TagMap::register(
            $this->app,
            \Apie\CmsApiDropdownOption\DropdownOptionProvider\EntityIdentifierOptionProvider::class,
            array(
              0 => 'Apie\\CmsApiDropdownOption\\DropdownOptionProvider\\DropdownOptionProviderInterface',
            )
        );
        $this->app->tag([\Apie\CmsApiDropdownOption\DropdownOptionProvider\EntityIdentifierOptionProvider::class], \Apie\CmsApiDropdownOption\DropdownOptionProvider\DropdownOptionProviderInterface::class);
    }
}
