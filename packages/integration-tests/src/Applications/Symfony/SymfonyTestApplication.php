<?php
namespace Apie\IntegrationTests\Applications\Symfony;

use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;

class SymfonyTestApplication implements TestApplicationInterface
{
    public function bootApplication(): void
    {

    }

    public function getServiceContainer(): ContainerInterface
    {
        return new Container();
    }

    public function cleanApplication(): void
    {

    }
}