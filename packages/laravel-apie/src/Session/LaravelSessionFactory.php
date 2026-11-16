<?php
namespace Apie\LaravelApie\Session;

use Apie\Core\Session\SessionFactoryInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Session\SymfonySessionDecorator;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use WeakMap;

class LaravelSessionFactory implements SessionFactoryInterface
{
    /** @var WeakMap<Application, SessionInterface>|null */
    private static ?WeakMap $mockSessions = null;

    public function __construct(private readonly Application $app)
    {
    }

    public function create(): SessionInterface
    {
        self::$mockSessions ??= new WeakMap();
        if ($this->app->bound('request')) {
            $request = $this->app->make('request');
            if ($request instanceof Request && $request->hasSession()) {
                return new SymfonySessionDecorator($request->session());
            }
        }
        if (isset(self::$mockSessions[$this->app])) {
            return self::$mockSessions[$this->app];
        }

        return self::$mockSessions[$this->app] = new Session(new MockArraySessionStorage());
    }
}
