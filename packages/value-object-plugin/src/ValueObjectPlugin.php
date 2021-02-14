<?php

namespace Apie\ValueObjectPlugin;


use Apie\Core\PluginInterfaces\NormalizerProviderInterface;
use Apie\Core\PluginInterfaces\SchemaProviderInterface;
use Apie\ValueObjectPlugin\Normalizers\ValueObjectNormalizer;
use Apie\ValueObjects\ValueObjectInterface;
use W2w\Lib\Apie\Plugins\ValueObject\Schema\ValueObjectSchemaBuilder;

class ValueObjectPlugin implements NormalizerProviderInterface, SchemaProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function getNormalizers(): array
    {
        return [
            new ValueObjectNormalizer()
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getDefinedStaticData(): array
    {
        return [
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getDynamicSchemaLogic(): array
    {
        return [
            ValueObjectInterface::class => new ValueObjectSchemaBuilder()
        ];
    }
}
