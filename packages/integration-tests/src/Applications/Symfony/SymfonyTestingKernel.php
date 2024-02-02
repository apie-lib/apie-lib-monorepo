<?php
namespace Apie\IntegrationTests\Applications\Symfony;

use Apie\ApieBundle\ApieBundle;
use Apie\ApieBundle\Security\ApieUserProvider;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\Kernel;

class SymfonyTestingKernel extends Kernel
{
    /**
     * @param array<string, mixed> $apieConfig
     */
    public function __construct(
        private array $apieConfig = [],
        private readonly bool $includeTwigBundle = false,
        private readonly bool $includeSecurityBundle = true
    ) {
        $this->apieConfig['enable_security'] ??= $this->includeSecurityBundle;
        if (!$this->includeTwigBundle) {
            $this->apieConfig['cms'] ??= [];
            $this->apieConfig['cms']['error_template'] = __DIR__ . '/../../../fixtures/symfony/templates/error.html';
        }
        parent::__construct('test', true);
    }

    public function getCacheDir(): string
    {
        return sys_get_temp_dir() . '/cache/' . md5(json_encode([$this->apieConfig, $this->includeTwigBundle, $this->includeSecurityBundle]));
    }
    
    public function registerBundles(): iterable
    {
        $res = [
            new FrameworkBundle(), // this is needed to have a functional http_kernel service.
            new ApieBundle()
        ];
        if ($this->includeTwigBundle) {
            $res[] = new TwigBundle();
        }
        if ($this->includeSecurityBundle) {
            $res[] = new SecurityBundle();
        }
        return $res;
    }

    private function getDefaultTwigTemplate(): string
    {
        return __DIR__ . '/../../../fixtures/symfony/templates';
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(function (ContainerBuilder $container) {
            $container->addDefinitions([
                InMemoryPersistentSessionStorageFactory::class => new Definition(InMemoryPersistentSessionStorageFactory::class),
            ]);
            $container->loadFromExtension('apie', $this->apieConfig);
            $container->loadFromExtension(
                'framework',
                [
                    'http_method_override' => false,
                    'secret' => '123456',
                    'session' => [
                        'enabled' => true,
                        'storage_factory_id' => InMemoryPersistentSessionStorageFactory::class,
                        'handler_id' => 'session.handler.native_file',
                        'use_cookies' => true,
                    ],
                    'router' => [
                        'resource' => '.',
                        'type' => 'apie'
                    ],
                    'csrf_protection' => false,
                ]
            );
            if ($this->includeTwigBundle) {
                $container->loadFromExtension(
                    'twig',
                    [
                        'default_path' => $this->getDefaultTwigTemplate(),
                    ]
                );
            }
            if ($this->includeSecurityBundle) {
                $container->loadFromExtension(
                    'security',
                    [
                        'providers' => [
                            'apie_user' => [
                                'id' => ApieUserProvider::class
                            ]
                        ],
                        'firewalls' => [
                            'dev' => ['pattern' =>  '^/(_(profiler|wdt)|css|images|js)/'],
                            'main' => ['lazy' => true, 'provider' => 'apie_user'],
                        ]
                    ]
                );
            }
        });
    }
}
