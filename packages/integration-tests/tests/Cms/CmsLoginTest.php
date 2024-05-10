<?php
namespace Apie\Tests\IntegrationTests\Cms;

use Apie\Common\Events\AddAuthenticationCookie;
use Apie\Common\IntegrationTestLogger;
use Apie\Common\ValueObjects\DecryptedAuthenticatedUser;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\UserIdentifier;
use Apie\IntegrationTests\Apie\TypeDemo\Resources\User;
use Apie\IntegrationTests\Applications\Laravel\LaravelTestApplication;
use Apie\IntegrationTests\IntegrationTestHelper;
use Apie\IntegrationTests\Interfaces\TestApplicationInterface;
use Apie\IntegrationTests\Requests\ActionMethodApiCall;
use Apie\IntegrationTests\Requests\EditRequestDecorator;
use Apie\IntegrationTests\Requests\FormSubmitCall;
use Apie\IntegrationTests\Requests\JsonFields\GetAndSetObjectField;
use Apie\IntegrationTests\Requests\JsonFields\GetPrimitiveField;
use Apie\IntegrationTests\Requests\JsonFields\SetPrimitiveField;
use Apie\IntegrationTests\Requests\TestRequestInterface;
use Apie\PhpunitMatrixDataProvider\MakeDataProviderMatrix;
use Apie\TextValueObjects\StrongPassword;
use Generator;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionMethod;
use ReflectionProperty;

class CmsLoginTest extends TestCase
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
        $testApplication->runApplicationTest(function (TestApplicationInterface $testApplication) {
            $user = new User(new UserIdentifier('test@example.nl'));
            $user->activate(
                (new ReflectionProperty(User::class, 'activationToken'))->getValue($user)->toNative(),
                new StrongPassword('Test@test2'),
                new StrongPassword('Test@test2'),
            );
            $loginRequest = new FormSubmitCall(
                'action/Authentication/verifyAuthentication',
                new BoundedContextId('types'),
                new GetAndSetObjectField(
                    '',
                    new SetPrimitiveField('username', 'test@example.nl'),
                    new SetPrimitiveField('password', 'Test@test2'),
                    new GetPrimitiveField('id', 'test@example.nl'),
                    new GetPrimitiveField('blocked', false),
                    new GetPrimitiveField('blockedReason', null),
                    new GetPrimitiveField('phoneNumber', null),
                ),
                '/cms/types/resource/User/test@example.nl',
                entities: [
                    $user
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
            $this->assertNotNull($testApplication->getLoggedInAs(), 'I should be logged in');
            $this->assertEquals('test@example.nl', $testApplication->getLoggedInAs()->getId()->toNative());
            $this->assertEquals('types', $testApplication->getLoggedInAs()->getBoundedContextId()->toNative());

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

    public function it_can_read_the_authorization_cookie_provider(): Generator
    {
        yield from $this->createDataProviderFrom(
            new ReflectionMethod($this, 'it_can_read_the_authorization_cookie'),
            new IntegrationTestHelper()
        );
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_can_read_the_authorization_cookie_provider
     * @test
     */
    public function it_can_read_the_authorization_cookie(TestApplicationInterface $testApplication)
    {
        if ($testApplication instanceof LaravelTestApplication) {
            $this->markTestIncomplete('does not work on laravel right now');
        }
        $testApplication->runApplicationTest(function (TestApplicationInterface $testApplication) {
            $user = new User(new UserIdentifier('test@example.nl'));
            $user->activate(
                (new ReflectionProperty(User::class, 'activationToken'))->getValue($user)->toNative(),
                new StrongPassword('Test@test2'),
                new StrongPassword('Test@test2'),
            );
            $testApplication->loginAs(DecryptedAuthenticatedUser::createFromEntity($user, new BoundedContextId('types'), time() + 77777));
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
                entities: [$user]
            );
            $currentUserRequest->bootstrap($testApplication);
            $response = $testApplication->httpRequest($currentUserRequest);
            if (!$currentUserRequest->isFakeDatalayer()) {
                $currentUserRequest->verifyValidResponse($response);
            } else {
                $this->assertEquals(200, $response->getStatusCode());
            }
        });
    }

    public function it_display_401_if_auth_cookie_contains_garbage_provider(): Generator
    {
        yield from $this->createDataProviderFrom(
            new ReflectionMethod($this, 'it_display_401_if_auth_cookie_contains_garbage'),
            new IntegrationTestHelper()
        );
    }

    /**
     * @runInSeparateProcess
     * @dataProvider it_display_401_if_auth_cookie_contains_garbage_provider
     * @test
     */
    public function it_display_401_if_auth_cookie_contains_garbage(TestApplicationInterface $testApplication)
    {
        $testApplication->runApplicationTest(function (TestApplicationInterface $testApplication) {
            $currentUserRequest = new ActionMethodApiCall(
                new BoundedContextId('types'),
                'me',
                new GetPrimitiveField('', null)
            );
            $currentUserRequest->bootstrap($testApplication);
            $currentUserRequest = new EditRequestDecorator(
                $currentUserRequest,
                function (ServerRequestInterface $request) {
                    return $request->withCookieParams([
                        AddAuthenticationCookie::COOKIE_NAME => 'garbage string',
                    ]);
                },
                function (ResponseInterface $response, TestRequestInterface $testRequest) {
                    $this->assertEquals(
                        401,
                        $response->getStatusCode(),
                        'Expect unauthorized, got: ' . $response->getBody()
                    );
                }
            );
            $response = $testApplication->httpRequest($currentUserRequest);
            $currentUserRequest->verifyValidResponse($response);
        });
    }
}
