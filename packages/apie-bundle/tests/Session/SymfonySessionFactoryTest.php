<?php
namespace Apie\Tests\ApieBundle\Session;

use Apie\ApieBundle\Session\SymfonySessionFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpKernel\KernelInterface;

class SymfonySessionFactoryTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_creates_a_mock_session_without_a_request(): void
    {
        $factory = new SymfonySessionFactory(new RequestStack(), $this->createMock(KernelInterface::class));

        $this->assertInstanceOf(Session::class, $factory->create());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_reuses_a_mock_session_for_the_same_request_stack(): void
    {
        $requestStack = new RequestStack();
        $kernel = $this->createMock(KernelInterface::class);
        $session = (new SymfonySessionFactory($requestStack, $kernel))->create();

        $this->assertSame($session, (new SymfonySessionFactory($requestStack, $kernel))->create());
        $this->assertNotSame(
            $session,
            (new SymfonySessionFactory(new RequestStack(), $this->createMock(KernelInterface::class)))->create()
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_uses_the_request_session(): void
    {
        $requestStack = new RequestStack();
        $kernel = $this->createMock(KernelInterface::class);
        $factory = new SymfonySessionFactory($requestStack, $kernel);
        $factory->create();

        $request = Request::create('/');
        $session = new Session(new MockArraySessionStorage());
        $request->setSession($session);
        $requestStack->push($request);

        $this->assertSame($session, $factory->create());
    }
}
