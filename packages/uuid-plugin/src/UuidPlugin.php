<?php

namespace Apie\UuidPlugin;

use Apie\Core\PluginInterfaces\NormalizerProviderInterface;
use Apie\Core\PluginInterfaces\SchemaProviderInterface;
use Apie\OpenapiSchema\Factories\SchemaFactory;
use Apie\UuidPlugin\Normalizers\UuidNormalizer;
use Ramsey\Uuid\UuidInterface;

class UuidPlugin implements NormalizerProviderInterface, SchemaProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function getNormalizers(): array
    {
        return [
            new UuidNormalizer()
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getDefinedStaticData(): array
    {
        return [
            UuidInterface::class => SchemaFactory::createStringSchema('uuid')
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getDynamicSchemaLogic(): array
    {
        return [];
    }
}
