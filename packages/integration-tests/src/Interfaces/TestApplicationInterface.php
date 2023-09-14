<?php
namespace Apie\IntegrationTests\Interfaces;

use Apie\IntegrationTests\Config\ApplicationConfig;
use Apie\IntegrationTests\Requests\TestRequestInterface;
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
     * Gets used Application config
     */
    public function getApplicationConfig(): ApplicationConfig;

    /**
     * Does a GET HTTP request on the application and returns the response.
     */
    public function httpRequestGet(string $uri): ResponseInterface;

    /**
     * Does a HTTP request on the application and returns the response.
     */
    public function httpRequest(TestRequestInterface $testRequest): ResponseInterface;
}
