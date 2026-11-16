<?php
namespace Apie\ApieBundle\Session;

use Apie\Core\Session\SessionFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpKernel\KernelInterface;
use WeakMap;

class SymfonySessionFactory implements SessionFactoryInterface
{
    /** @var WeakMap<KernelInterface, SessionInterface>|null */
    private static ?WeakMap $mockSessions = null;

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly KernelInterface $kernel
    ) {
    }

    public function create(): SessionInterface
    {
        self::$mockSessions ??= new WeakMap();
        $request = $this->requestStack->getMainRequest();
        if ($request && $request->hasSession()) {
            return $request->getSession();
        }
        if (isset(self::$mockSessions[$this->kernel])) {
            return self::$mockSessions[$this->kernel];
        }

        return self::$mockSessions[$this->kernel] = new Session(new MockArraySessionStorage());
    }
}
