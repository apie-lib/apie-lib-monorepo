<?php
namespace Apie\IntegrationTests\Interfaces;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

interface TestApplicationInterface
{
    /**
     * Boot application. Should be called at the start of the test.
     */
    public function bootApplication(): void;

    /**
     * Gets service container of application. Should be used as little as possible.
     */
    public function getServiceContainer(): ContainerInterface;

    /**
     * Cleans up application. Should be called at the end of the test.
     */
    public function cleanApplication(): void;

    /**
     * Does a HTTP request on the application and returns the response.
     */
    public function httpRequestGet(string $uri): ResponseInterface;
}
