<?php
namespace Apie\Maker\EventListeners;

use Apie\Common\Events\ApieResourceCreated;
use Apie\Maker\BoundedContext\Resources\CodeGeneratedLog;
use Symfony\Bundle\FrameworkBundle\Command\CacheWarmupCommand;
use Symfony\Component\DependencyInjection\Dumper\Preloader;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerAggregate;
use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;
use Symfony\Component\HttpKernel\Kernel;

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

        @unlink($cacheDir . '/url_generating_routes.php');
        @unlink($cacheDir . '/url_generating_routes.php.meta');
        @unlink($cacheDir . '/url_matching_routes.php');
        @unlink($cacheDir . '/url_matching_routes.php.meta');
        $this->cacheWarmer->enableOptionalWarmers();
        

        if ($this->kernel instanceof WarmableInterface) {
            $this->kernel->warmUp($cacheDir);
        }

        $preload = $this->cacheWarmer->warmUp($cacheDir);
        $buildDir = $this->kernel->getContainer()->getParameter('kernel.build_dir');
        $preloadFile = $buildDir.'/'.$this->kernel->getContainer()->getParameter('kernel.container_class').'.preload.php';
        if ($preload && $cacheDir === $buildDir && file_exists($preloadFile)) {
            Preloader::append($preloadFile, $preload);
        }
    }
}
