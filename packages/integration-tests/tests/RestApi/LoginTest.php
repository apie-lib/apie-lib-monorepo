<?php
namespace Apie\Tests\IntegrationTests\RestApi;

use Apie\Common\IntegrationTestLogger;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\UserIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\User;
use Apie\IntegrationTests\Applications\Symfony\SymfonyTestApplication;
use Apie\IntegrationTests\IntegrationTestHelper;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\IntegrationTests\Requests\ActionMethodApiCall;
use Apie\IntegrationTests\Requests\JsonFields\GetAndSetObjectField;
use Apie\IntegrationTests\Requests\JsonFields\GetAndSetPrimitiveField;
use Apie\IntegrationTests\Requests\JsonFields\GetPrimitiveField;
use Apie\IntegrationTests\Requests\JsonFields\GetUuidField;
use Apie\IntegrationTests\Requests\JsonFields\SetPrimitiveField;
use Apie\IntegrationTests\Requests\ValidCreateResourceApiCall;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use Apie\TextValueObjects\StrongPassword;
use Generator;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use ReflectionProperty;

class LoginTest extends TestCase
{
    use MakeDataProviderMatrix;

    public function it_can_login_by_convention_provider(): Generator
    {
        yield from $this->createDataProviderFrom(
            new ReflectionMethod($this, 'it_can_login_by_convention'),
            new IntegrationTestHelper()
        );
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_can_login_by_convention_provider
     * @test
     */
    public function it_can_login_by_convention(TestApplicationInterface $testApplication)
    {
        if ($testApplication instanceof SymfonyTestApplication) {
            $this->markTestIncomplete('Symfony tests do not work properly with session yet');
        }
        if (!$testApplication->getApplicationConfig()->doesIncludeSecurity()) {
            $this->markTestSkipped('Symfony without security has no working login functionality');
        }
        $testApplication->runApplicationTest(function (TestApplicationInterface $testApplication) {
            $user = new User(new UserIdentifier('test@example.nl'));
            $user->activate(
                (new ReflectionProperty(User::class, 'activationToken'))->getValue($user)->toNative(),
                new StrongPassword('Test@test2'),
                new StrongPassword('Test@test2'),
            );
            $loginRequest = new ActionMethodApiCall(
                new BoundedContextId('types'),
                'Authentication/verifyAuthentication',
                new GetAndSetObjectField(
                    '',
                    new SetPrimitiveField('username', 'test@example.nl'),
                    new SetPrimitiveField('password', 'Test@test2'),
                    new GetPrimitiveField('id', 'test@example.nl'),
                    new GetPrimitiveField('blocked', false),
                    new GetPrimitiveField('blockedReason', null),
                    new GetPrimitiveField('phoneNumber', null),
                ),
                entities: [
                    $user,
                ]
            );
            $loginRequest->bootstrap($testApplication);
            $response = $testApplication->httpRequest($loginRequest);
            if ($loginRequest->isFakeDatalayer()) {
                $this->assertEquals(500, $response->getStatusCode());
                $this->assertEquals('User is not activated yet', IntegrationTestLogger::getLoggedException()?->getMessage());
                return;
            }
            $loginRequest->verifyValidResponse($response);

            $currentUserRequest = new ActionMethodApiCall(
                new BoundedContextId('types'),
                'me',
                new GetAndSetObjectField(
                    '',
                    new GetPrimitiveField('id', 'test@example.nl'),
                    new GetPrimitiveField('blocked', false),
                    new GetPrimitiveField('blockedReason', null),
                    new GetPrimitiveField('phoneNumber', null),
                ),
            );
            $response = $testApplication->httpRequest($currentUserRequest);
            $currentUserRequest->verifyValidResponse($response);
        });
    }
}