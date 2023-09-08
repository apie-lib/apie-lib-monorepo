<?php
namespace Apie\IntegrationTests\Interfaces;

use Psr\Container\ContainerInterface;

interface TestApplicationInterface
{
    public function bootApplication(): void;

    public function getServiceContainer(): ContainerInterface;

    public function cleanApplication(): void;
}