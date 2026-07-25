<?php
namespace Apie\Core\Translator\ValueObjects;

use Apie\Core\Attributes\Description;
use Apie\Core\Attributes\ExampleValue;
use Apie\Core\Translator\Enums\Pluralization;
use ICanBoogie\Inflector;
use Symfony\Component\String\Inflector\EnglishInflector;
use Symfony\Component\String\Inflector\FrenchInflector;
use Symfony\Component\String\Inflector\SpanishInflector;

#[Description('Name of resource')]
#[ExampleValue('apie.bounded.test.resource.user.name.singular')]
class ResourceName extends AbstractTranslation
{
    protected const MIDDLE_REGEX = 'name';

    public function getFallbackText(string $locale = 'en'): string
    {
        $id = $this->prefix->getResourceIdentifier();
        $plural = $this->suffix->getPluralization();
        if ($id) {
            $inflector = Inflector::get($locale);
            return match ($plural) {
                Pluralization::Singular => $inflector->singularize(ucfirst($id->humanize())),
                Pluralization::Plural => $inflector->pluralize(ucfirst($id->humanize())),
                default => ucfirst($id->humanize()),
            };
        }
        return 'Resource';
    }
}
