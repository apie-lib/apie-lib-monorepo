<?php
namespace Apie\IntegrationTests\Applications\Symfony;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageFactoryInterface;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use WeakMap;

class InMemoryPersistentSessionStorageFactory implements SessionStorageFactoryInterface
{
    /** @var WeakMap<KernelInterface, SessionStorageInterface>|null */
    private static ?WeakMap $sessionStorages = null;

    public function __construct(private readonly KernelInterface $kernel)
    {
    }

    public function createStorage(?Request $request): SessionStorageInterface
    {
        self::$sessionStorages ??= new WeakMap();
        if (isset(self::$sessionStorages[$this->kernel])) {
            return self::$sessionStorages[$this->kernel];
        }

        return self::$sessionStorages[$this->kernel] = new MockArraySessionStorage();
    }
}
