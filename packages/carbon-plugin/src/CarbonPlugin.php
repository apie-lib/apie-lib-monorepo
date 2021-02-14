<?php
namespace Apie\CarbonPlugin;

use Apie\CarbonPlugin\Normalizers\CarbonNormalizer;
use Apie\Core\PluginInterfaces\NormalizerProviderInterface;
use Apie\Core\PluginInterfaces\SchemaProviderInterface;
use Apie\OpenapiSchema\Contract\SchemaContract;
use Apie\OpenapiSchema\Factories\SchemaFactory;
use DateTimeInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

/**
 * Plugin that adds support for Carbon. See https://carbon.nesbot.com/
 */
final class CarbonPlugin implements NormalizerProviderInterface, SchemaProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function getNormalizers(): array
    {
        return [
                new CarbonNormalizer(
                [
                    DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s'
                ]
            )
        ];
    }

    /**
     * @return SchemaContract[]
     */
    public function getDefinedStaticData(): array
    {
        AnnotationReader::addGlobalIgnoredName('alias');
        return [
            DateTimeInterface::class => SchemaFactory::createStringSchema('date-time'),
        ];
    }

    /**
     * @return callable[]
     */
    public function getDynamicSchemaLogic(): array
    {
        return [];
    }
}
