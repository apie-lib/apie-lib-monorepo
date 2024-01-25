<?php
namespace Apie\IntegrationTests\Applications\Symfony;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageFactoryInterface;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;

class InMemoryPersistentSessionStorageFactory implements SessionStorageFactoryInterface
{
    private SessionStorageInterface $sessionStorage;

    public function createStorage(?Request $request): SessionStorageInterface
    {
        if (!isset($this->sessionStorage)) {
            $this->sessionStorage = new MockArraySessionStorage();
        }

        return $this->sessionStorage;
    }
}