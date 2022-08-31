<?php
namespace Apie\ApieBundle\Wrappers;

use Apie\Common\Interfaces\RouteDefinitionProviderInterface;
use Apie\Common\RouteDefinitions\ChainedRouteDefinitionsProvider;
use Apie\Core\ContextBuilders\ContextBuilderFactory;
use Apie\Core\ContextBuilders\ContextBuilderInterface;
use Apie\Faker\ApieObjectFaker;
use Apie\Faker\Interfaces\ApieClassFaker;
use Faker\Factory;
use Faker\Generator;

/**
 * This is basically a work around around !tagged_iterators support with variadic arguments.
 */
final class GeneralServiceFactory
{
    private function __construct()
    {
    }

    /**
     * @param iterable<int, ContextBuilderInterface> $contextBuilders
     */
    public static function createContextBuilderFactory(iterable $contextBuilders): ContextBuilderFactory
    {
        return new ContextBuilderFactory(...$contextBuilders);
    }

    /**
     * @param iterable<int, RouteDefinitionProviderInterface> $routeDefinitionProviders
     */
    public static function createRoutedDefinitionProvider(iterable $routeDefinitionProviders): RouteDefinitionProviderInterface
    {
        return new ChainedRouteDefinitionsProvider(...$routeDefinitionProviders);
    }

    /**
     * @param iterable<int, ApieClassFaker<object>> $fakeProviders
     */
    public static function createFaker(iterable $fakeProviders): Generator
    {
        $faker = Factory::create();
        $faker->addProvider(ApieObjectFaker::createWithDefaultFakers($faker, ...$fakeProviders));
    
        return $faker;
    }
}
