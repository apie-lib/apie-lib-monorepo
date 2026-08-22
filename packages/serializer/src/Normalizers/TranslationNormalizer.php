<?php
namespace Apie\Serializer\Normalizers;

use Apie\Core\Translator\ApieTranslatorInterface;
use Apie\Core\Translator\Lists\TranslationStringSet;
use Apie\Core\Translator\ValueObjects\AbstractTranslation;
use Apie\Core\ValueObjects\Utils;
use Apie\Serializer\Context\ApieSerializerContext;
use Apie\Serializer\Interfaces\NormalizerInterface;

class TranslationNormalizer implements NormalizerInterface
{
    public function supportsNormalization(
        mixed $object,
        ApieSerializerContext $apieSerializerContext
    ): bool {
        return $object instanceof AbstractTranslation || $object instanceof TranslationStringSet;
    }
    public function normalize(
        mixed $object,
        ApieSerializerContext $apieSerializerContext
    ): string {
        $translator = $apieSerializerContext->getContext()->getContext(ApieTranslatorInterface::class, false);

        if ($translator instanceof ApieTranslatorInterface) {
            return $translator->getGeneralTranslation($apieSerializerContext->getContext(), $object) ?? Utils::toString($object);
        }

        return Utils::toString($object);
    }
}
