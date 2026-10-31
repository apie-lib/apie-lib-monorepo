<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Resources;

use Apie\Core\Attributes\StoreOptions;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\Lists\StringHashmap;
use Apie\Core\Translator\ValueObjects\AbstractTranslation;
use Apie\IanaValueObjects\LanguageAndRegion;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\TranslationAwareIdentifier;

class TranslationAware implements EntityInterface
{
    private TranslationAwareIdentifier $id;

    #[StoreOptions(mutableListField: true)]
    private StringHashmap $translations;

    public function __construct(
        private readonly AbstractTranslation $translation
    ) {
        $this->id = TranslationAwareIdentifier::createRandom();
        $this->translations = (new StringHashmap())->toMutable();
    }

    public function getId(): TranslationAwareIdentifier
    {
        return $this->id;
    }

    public function getTranslation(): AbstractTranslation
    {
        return $this->translation;
    }

    public function setText(LanguageAndRegion $language, string $text): TranslationAware
    {
        $this->translations[$language->toNative()] = $text;

        return $this;
    }

    public function getText(LanguageAndRegion $language): ?string
    {
        return $this->translations[$language->toNative()] ?? null;
    }
}
