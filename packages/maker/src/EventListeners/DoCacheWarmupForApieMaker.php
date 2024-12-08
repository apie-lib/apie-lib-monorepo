<?php
namespace Apie\Maker\EventListeners;

use Apie\Common\Events\ApieResourceCreated;
use Apie\Maker\BoundedContext\Resources\CodeGeneratedLog;
use Symfony\Bundle\FrameworkBundle\Command\CacheWarmupCommand;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerAggregate;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelEvents;

final class DoCacheWarmupForApieMaker implements EventSubscriberInterface
{
    public function __construct(
        private readonly CacheWarmerAggregate $cacheWarmer,
        private readonly Kernel $kernel
    ) {
    }
    /**
     * @return array<string, array<int, string>>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ApieResourceCreated::class => ['onResourceCreated'],
        ];
    }

    /**
     * Code is similar to the code in CacheWarmupCommand
     *
     * @see CacheWarmupCommand::execute
     */
    public function onResourceCreated(ApieResourceCreated $resourceCreated): void
    {
        if (!($resourceCreated->resource instanceof CodeGeneratedLog)) {
            return;
        }
        $cacheDir = $this->kernel->getContainer()->getParameter('kernel.cache_dir');

        /** @var EventDispatcherinterface */
        $dispatcher = $this->kernel->getContainer()->get('event_dispatcher');
        $dispatcher->addListener(KernelEvents::TERMINATE, function () use ($cacheDir) {
            @unlink($cacheDir . '/url_generating_routes.php');
            @unlink($cacheDir . '/url_matching_routes.php');
            @unlink($cacheDir . '/url_generating_routes.php.meta');
            @unlink($cacheDir . '/url_matching_routes.php.meta');
        });
    }
}
