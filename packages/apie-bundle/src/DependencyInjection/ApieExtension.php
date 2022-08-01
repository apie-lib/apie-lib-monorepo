<?php
namespace Apie\ApieBundle\DependencyInjection;

use Apie\ApieBundle\ValueObjects\EntityNamespace;
use Apie\Core\BoundedContext\BoundedContext;
use Apie\Core\BoundedContext\BoundedContextHashmap;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Lists\ReflectionClassList;
use Apie\Core\Lists\ReflectionMethodList;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\GlobResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Finder\Finder;

class ApieExtension extends Extension
{
    private array $dependencies = [
        'enable_rest_api' => [
            'rest_api.yaml',
            'schema_generator.yaml',
            'serializer.yaml',
        ]
    ];

    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../resources/config'));
        $loader->load('services.yaml');
        $loader->load('psr7.yaml');
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('apie.bounded_contexts', $config['bounded_contexts']);
        $loaded = [];
        foreach ($this->dependencies as $configName => $dependencyList) {
            if ($config[$configName]) {
                foreach ($dependencyList as $dependency) {
                    if (!isset($loaded[$dependency])) {
                        $loaded[$dependency] = true;
                        $loader->load($dependency);
                    }
                }
            }
        }
    }
}
