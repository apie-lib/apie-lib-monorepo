<?php
namespace Apie\IntegrationTests;

use Apie\Core\BoundedContext\BoundedContextId;
use Apie\DoctrineEntityDatalayer\DoctrineEntityDatalayer;
use Apie\Faker\Datalayers\FakerDatalayer;
use Apie\IntegrationTests\Concerns\CreatesApieBoundedContext;
use Apie\IntegrationTests\Concerns\CreatesApplications;
use Apie\IntegrationTests\Config\ApplicationConfig;
use Apie\IntegrationTests\Config\Enums\DatalayerImplementation;
use Apie\IntegrationTests\Requests\ActionMethodApiCallExpectingStatusCodes;
use Apie\IntegrationTests\Requests\JsonFields\GetAndSetObjectField;
use Apie\IntegrationTests\Requests\TestRequestInterface;

/**
 * Dedicated helper used only by ErrorRenderTest, so these error scenario fixtures are kept
 * isolated from the main IntegrationTestHelper matrix (e.g. OpenAPI spec validation).
 *
 * @codeCoverageIgnore
 */
class ErrorRenderTestHelper
{
    use CreatesApplications;
    use CreatesApieBoundedContext;

    public function createDbLayerImplementation(): ?DatalayerImplementation
    {
        return class_exists(DoctrineEntityDatalayer::class) ? DatalayerImplementation::DB_DATALAYER : null;
    }

    public function createFakerLayerImplementation(): ?DatalayerImplementation
    {
        return class_exists(FakerDatalayer::class) ? DatalayerImplementation::FAKER : null;
    }

    public function createInMemoryLayerImplementation(): DatalayerImplementation
    {
        return DatalayerImplementation::IN_MEMORY;
    }

    public function createFullFrameworkConfig(DatalayerImplementation $datalayerImplementation): ApplicationConfig
    {
        return new ApplicationConfig(
            true,
            true,
            $datalayerImplementation
        );
    }

    /**
     * Calling a method restricted to logged in users while not logged in should result in a
     * 401/403, not a 500. Regression test for a bug where RunAction's AUTHORIZATION_ERROR
     * status fell through to the default 500 status code.
     */
    public function createAuthorizationErrorTestRequest(): TestRequestInterface
    {
        return new ActionMethodApiCallExpectingStatusCodes(
            new BoundedContextId('types'),
            'Authentication/restrictedToLoggedInUsers',
            new GetAndSetObjectField(''),
            [401, 403],
            method: 'GET'
        );
    }

    /**
     * Calling an action without providing the required fields should result in a 422
     * validation error, not a 500.
     */
    public function createValidationErrorTestRequest(): TestRequestInterface
    {
        return new ActionMethodApiCallExpectingStatusCodes(
            new BoundedContextId('types'),
            'Authentication/verifyAuthentication',
            new GetAndSetObjectField(''),
            [422]
        );
    }

    /**
     * Requesting a resource that does not exist should result in a 404, not a 500.
     */
    public function createResourceNotFoundTestRequest(): TestRequestInterface
    {
        return new ActionMethodApiCallExpectingStatusCodes(
            new BoundedContextId('types'),
            'User/does-not-exist@example.com',
            new GetAndSetObjectField(''),
            [404],
            method: 'GET'
        );
    }
}
