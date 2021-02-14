<?php


namespace Apie\DateTimePlugin;

use Apie\Core\PluginInterfaces\NormalizerProviderInterface;
use Apie\Core\PluginInterfaces\SchemaProviderInterface;
use Apie\OpenapiSchema\Contract\SchemaContract;
use Apie\OpenapiSchema\Factories\SchemaFactory;
use DateTimeInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class DateTimePlugin implements NormalizerProviderInterface, SchemaProviderInterface
{
    /**
     * @return NormalizerInterface[]|DenormalizerInterface[]
     */
    public function getNormalizers(): array
    {
        return [
            new DateTimeNormalizer([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s'])
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
