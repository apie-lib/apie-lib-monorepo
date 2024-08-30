<?php
namespace Apie\IntegrationTests\Applications\Symfony;

use Apie\ApieBundle\ApieBundle;
use Apie\ApieBundle\Security\ApieUserAuthenticator;
use Apie\ApieBundle\Security\ApieUserProvider;
use Apie\Core\Other\FileWriterInterface;
use Apie\Core\Other\MockFileWriter;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

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
        return sys_get_temp_dir()
            . '/cache/'
            . md5(
                json_encode(
                    [
                    $this->apieConfig,
                    $this->includeTwigBundle,
                    $this->includeSecurityBundle,
                    getenv('PHPUNIT_LOG_INTEGRATION_OUTPUT') ? 1 : 0
                ]
                )
            );
    }
    
    public function registerBundles(): iterable
    {
        $res = [
            new FrameworkBundle(), // this is needed to have a functional http_kernel service.
            new ApieBundle(),
            new MonologBundle(), // some errors are discarded for response, but still logged.
            new DoctrineBundle(), // maybe make this optional as the bundle also works without
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

    public function __wakeup()
    {
        if (\is_object($this->environment) || \is_object($this->debug)) {
            throw new \BadMethodCallException('Cannot unserialize '.__CLASS__);
        }

        $this->__construct($this->apieConfig, $this->environment, $this->debug);
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(function (ContainerBuilder $container) {
            $container->addDefinitions([
                InMemoryPersistentSessionStorageFactory::class => new Definition(InMemoryPersistentSessionStorageFactory::class),
                FileWriterInterface::class => (new Definition(MockFileWriter::class))->setPublic(true),
            ]);
            $container->loadFromExtension('apie', $this->apieConfig);
            $container->loadFromExtension('doctrine', ['orm' => ['auto_mapping' => true], 'dbal' => []]);
            $container->loadFromExtension('monolog', [
                'handlers' => [
                    'file_log' => [
                        'type' => 'stream',
                        'path' => $this->getCacheDir() . 'log',
                        'level' => getenv('PHPUNIT_LOG_INTEGRATION_OUTPUT') ? 'debug' : 'error',
                    ]
                ]
            ]);
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
                        'cookie_secure' => 'auto',
                        'cookie_samesite' => 'lax',
                    ],
                    'php_errors' => [
                        'log' => false,
                    ],
                    'router' => [
                        'resource' => '.',
                        'type' => 'apie'
                    ],
                    'uid' => [
                        'default_uuid_version' => 7,
                        'time_based_uuid_version' => 7,
                    ],
                    'csrf_protection' => false,
                    'handle_all_throwables' => true,
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
                        'password_hashers' => [
                            PasswordAuthenticatedUserInterface::class => [
                                'algorithm' => 'auto',
                                'cost' => 4,
                                'time_cost' => 3,
                                'memory_cost' => 10,
                            ],
                        ],
                        'providers' => [
                            'apie_user' => [
                                'id' => ApieUserProvider::class
                            ]
                        ],
                        'firewalls' => [
                            'dev' => [
                                'pattern' =>  '^/(_(profiler|wdt)|css|images|js)/',
                                'security' => false,
                            ],
                            'main' => [
                                'lazy' => true,
                                'provider' => 'apie_user',
                                'custom_authenticators' => [ApieUserAuthenticator::class]
                            ],
                        ]
                    ]
                );
            }
        });
    }
}
