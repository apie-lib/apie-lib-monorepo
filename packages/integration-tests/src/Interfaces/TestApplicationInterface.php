<?php
namespace Apie\IntegrationTests\Interfaces;

use Apie\Common\ValueObjects\DecryptedAuthenticatedUser;
use Apie\Core\Entities\EntityInterface;
use Apie\IntegrationTests\Config\ApplicationConfig;
use Apie\IntegrationTests\Requests\TestRequestInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Console\Application;

interface TestApplicationInterface
{
    /**
     * Boot application. Should be called at the start of the test.
     */
    public function bootApplication(): void;

    /**
     * Starts bootApplication, runs test and does cleanApplication.
     */
    public function ItRunsApplications(callable $test): void;

    /**
     * Get console command application for testing console commands.
     */
    public function getConsoleApplication(): Application;

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
     * Login as a specific user.
     *
     * @param DecryptedAuthenticatedUser<EntityInterface> $user
     */
    public function loginAs(DecryptedAuthenticatedUser $user): void;

    /**
     * Forget that you are logged in.
     */
    public function logout(): void;

    /**
     * Get logged in as user.
     * @return DecryptedAuthenticatedUser<EntityInterface>|null
     */
    public function getLoggedInAs(): ?DecryptedAuthenticatedUser;

    /**
     * Does a GET HTTP request on the application and returns the response.
     */
    public function httpRequestGet(string $uri): ResponseInterface;

    /**
     * Does a HTTP request on the application and returns the response.
     */
    public function httpRequest(TestRequestInterface $testRequest): ResponseInterface;
}
