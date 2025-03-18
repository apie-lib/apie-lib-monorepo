<?php
namespace Apie\TwigTemplateLayoutRenderer\Extension;

use Apie\Core\ApieLib;
use Apie\Core\Context\ApieContext;
use Apie\Core\Translator\ApieTranslator;
use Apie\Core\Translator\ApieTranslatorInterface;
use Apie\Core\Translator\Lists\TranslationStringSet;
use Apie\Core\Translator\ValueObjects\TranslationString;
use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\HtmlBuilders\Interfaces\ComponentInterface;
use Apie\TwigTemplateLayoutRenderer\TwigRenderer;
use LogicException;
use ReflectionClass;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\Runtime\EscaperRuntime;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ComponentHelperExtension extends AbstractExtension
{
    /** @var ComponentInterface[] */
    private array $componentsHandled = [];

    /** @var TwigRenderer[] */
    private array $renderers = [];

    /** @var ApieContext[] */
    private array $contexts = [];

    public function selectComponent(
        TwigRenderer $renderer,
        ComponentInterface $component,
        ApieContext $apieContext
    ): void {
        $this->renderers[] = $renderer;
        $this->componentsHandled[] = $component;
        $this->contexts[] = $apieContext;
    }

    public function deselectComponent(ComponentInterface $component): void
    {
        if (end($this->componentsHandled) !== $component) {
            throw new LogicException('Last component is not the one being deselected');
        }
        array_pop($this->componentsHandled);
        array_pop($this->renderers);
        array_pop($this->contexts);
    }

    public function apieConstant(string $constantName): mixed
    {
        $refl = new ReflectionClass(ApieLib::class);
        return $refl->getConstant($constantName);
    }

    public function translate(string|TranslationString|TranslationStringSet $translation): string
    {
        $apieContext = $this->getCurrentContext();
        $translator = $apieContext->getContext(ApieTranslatorInterface::class, false) ?? ApieTranslator::create();
        try {
            return $translator->getGeneralTranslation(
                $apieContext,
                is_string($translation)
                    ? new TranslationString($translation)
                    : $translation
            ) ?? $translation;
        } catch (InvalidStringForValueObjectException) {
            if ($translation instanceof TranslationStringSet) {
                return $translation->first();
            }
            return (string) $translation;
        }
    }

    public function safeJsonEncode(mixed $data): string
    {
        return json_encode($data, JSON_HEX_QUOT|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS);
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('safe_json_encode', [$this, 'safeJsonEncode'], ['is_safe' => ['all']]),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('component', [$this, 'component'], ['is_safe' => ['all']]),
            new TwigFunction('isPrototyped', [$this, 'isPrototyped']),
            new TwigFunction('apieConstant', [$this, 'apieConstant']),
            new TwigFunction('translate', [$this, 'translate'], []),
            new TwigFunction('property', [$this, 'property'], []),
            new TwigFunction('assetUrl', [$this, 'assetUrl'], []),
            new TwigFunction('assetContent', [$this, 'assetContent'], ['is_safe' => ['all']]),
            new TwigFunction(
                'renderValidationError',
                [$this, 'renderValidationError'],
                ['needs_environment' => true, 'is_safe' => ['all']]
            ),
        ];
    }

    public function renderValidationError(
        Environment $env,
        mixed $value,
        array|string|null $validationError
    ): string {
        if ($validationError === null) {
            return '';
        }
        $escaper = $env->getRuntime(EscaperRuntime::class);
        $valueAttr = '';
        $valueScript = '';
        if ($value !== null) {
            if (is_string($value)) {
                $valueAttr = ' exact-match="' . $escaper->escape($value, 'html_attr', null, false) . '"';
            } else {
                $valueAttr = ' class="unhandled-constraint"';
                $valueScript = '<script>
(function (elm) {
    elm.classList.remove("unhandled-constraint");
    elm.value = ' . str_replace('<', '&lt;', json_encode($value)) . ';
}(document.querySelector(".unhandled-constraint"));
                </script>';
            }
        }

        if (is_string($validationError)) {
            $escapedValidationError = $escaper->escape($validationError, 'html_attr', null, false);
            return '<apie-constraint-check-definition message="'
                . $escapedValidationError
                . '"'
                . $valueAttr
                . '></apie-constraint-check-definition>'
                . $valueScript;
        }
        return '';
    }

    private function getCurrentContext(): ApieContext
    {
        if (empty($this->contexts)) {
            throw new LogicException('No component is selected');
        }
        return end($this->contexts);
    }

    private function getCurrentRenderer(): TwigRenderer
    {
        if (empty($this->renderers)) {
            throw new LogicException('No component is selected');
        }
        return end($this->renderers);
    }

    private function getCurrentComponent(): ComponentInterface
    {
        if (empty($this->componentsHandled)) {
            throw new LogicException('No component is selected');
        }
        return end($this->componentsHandled);
    }

    public function assetContent(string $filename): string
    {
        return $this->getCurrentRenderer()->getAssetContents($filename);
    }

    public function assetUrl(string $filename): string
    {
        return $this->getCurrentRenderer()->getAssetUrl($filename);
    }

    public function property(string $attributeKey): mixed
    {
        return $this->getCurrentComponent()->getAttribute($attributeKey);
    }

    public function component(string $componentName): string
    {
        return $this->getCurrentRenderer()->render(
            $this->getCurrentComponent()->getComponent($componentName),
            $this->getCurrentContext()
        );
    }

    public function isPrototyped(): bool
    {
        $attrs = $this->getCurrentComponent()->getAttribute('additionalAttributes');
        return is_array($attrs) ? ((bool) $attrs['prototyped'] ?? false) : false;
    }
}
