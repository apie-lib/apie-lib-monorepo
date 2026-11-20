<?php
namespace Apie\Tests\LaravelApie;

use Apie\Common\ApieFacade;
use Apie\Common\Events\AddAuthenticationCookie;
use Apie\Core\Session\SessionFactoryInterface;
use Apie\LaravelApie\ApieServiceProvider;
use Apie\LaravelApie\Config\ValidateAndSanitizeConfig;
use Apie\LaravelApie\Session\LaravelSessionFactory;
use Apie\LaravelApie\Wrappers\Security\ApieUserDecorator;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Session\ArraySessionHandler;
use Illuminate\Session\Store;
use Illuminate\Session\SymfonySessionDecorator;
use Orchestra\Testbench\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;

final class LaravelApieTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [ApieServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        tap($app->make('config'), function (Repository $config) {
            $config->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
            $config->set('apie.encryption_key', 'test');
            $config->set('apie.enable_cms', false);
            $config->set('apie.enable_cms_dropdown', true);
            $config->set('apie.enable_security', true);
            $config->set(
                'apie.bounded_contexts',
                [
                    'default' => [
                        'entities_folder' => __DIR__ . '/Fixtures/Entities',
                        'entities_namespace' => 'Apie\\Tests\\LaravelApie\\Fixtures\\Entities\\',
                        'actions_folder' => __DIR__ . '/Fixtures/Actions',
                        'actions_namespace' => 'Apie\\Tests\\LaravelApie\\Fixtures\\Actions\\',
                        'policies_folder' => __DIR__ . '/Fixtures/Policies',
                        'policies_namespace' => 'Apie\\Tests\\LaravelApie\\Fixtures\\Policies\\',
                    ],
                ]
            );
            $config->set(
                'apie.scan_bounded_contexts',
                [
                ]
            );
            $config->set('auth.guards.web', ['driver' => 'session', 'provider' => 'apie']);
            $config->set('auth.providers.apie', ['driver' => 'apie', 'model' => ApieUserDecorator::class]);
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    public function it_can_register_apie_as_a_service()
    {
        $this->assertInstanceOf(ApieFacade::class, resolve('apie'));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    public function the_facade_can_login_as_a_user()
    {
        $response = $this->postJson('/api/default/mockLogin', [
            'user' => [
                'id' => 'admin',
            ]
        ]);
        $response->assertOk();
        $cookie = $response->getCookie(AddAuthenticationCookie::COOKIE_NAME, decrypt: false);
        $this->assertNotNull($cookie);
        $response->assertJson([
            'id' => 'admin',
        ]);
    
        $response = $this->withUnencryptedCookies([$cookie->getName() => $cookie->getValue()])
            ->get('/api/default/me');
        $response->assertOk();
        $response->assertJson([
            'id' => 'admin',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    public function testValidateAndSanitizeConfig(): void
    {
        $input = [
            'enable_security' => true,
        ];
        $result = ValidateAndSanitizeConfig::process($input);
        $this->assertArrayHasKey('enable_security', $result);
        $this->assertTrue($result['enable_security']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    public function it_can_view_swagger_ui()
    {
        $response = $this->get('/api/default/openapi.yaml');
        $response->assertOk();
        $response->assertSeeText('TestEntity-post');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    public function it_can_use_rest_api_when_cms_is_disabled_and_dropdown_is_enabled(): void
    {
        $this->assertFalse(config('apie.enable_cms'));
        $response = $this->get('/api/default/openapi.yaml');
        $response->assertOk();
        $response->assertSeeText('cms.dropdown_options');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    public function the_session_factory_uses_a_mock_session_without_a_request_session(): void
    {
        $this->app->instance('request', Request::create('/'));

        $session = $this->app->make(SessionFactoryInterface::class)->create();
        $this->assertInstanceOf(Session::class, $session);
        $this->assertSame($session, (new LaravelSessionFactory($this->app))->create());

        $request = Request::create('/');
        $store = new Store('test', new ArraySessionHandler(120));
        $request->setLaravelSession($store);
        $this->app->instance('request', $request);

        $requestSession = (new LaravelSessionFactory($this->app))->create();
        $this->assertInstanceOf(SymfonySessionDecorator::class, $requestSession);
        $this->assertNotSame($session, $requestSession);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    public function the_session_factory_uses_the_laravel_request_session(): void
    {
        $request = Request::create('/');
        $store = new Store('test', new ArraySessionHandler(120));
        $request->setLaravelSession($store);
        $this->app->instance('request', $request);

        $session = $this->app->make(SessionFactoryInterface::class)->create();
        $this->assertInstanceOf(SymfonySessionDecorator::class, $session);
        $session->set('session_factory_test', 'value');
        $this->assertSame('value', $store->get('session_factory_test'));
    }
}
