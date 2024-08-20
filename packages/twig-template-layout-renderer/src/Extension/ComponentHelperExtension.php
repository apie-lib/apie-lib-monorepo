<?php
namespace Apie\TwigTemplateLayoutRenderer\Extension;

use Apie\Core\ApieLib;
use Apie\Core\Context\ApieContext;
use Apie\Core\Translator\ApieTranslator;
use Apie\Core\Translator\ApieTranslatorInterface;
use Apie\Core\Translator\ValueObjects\TranslationString;
use Apie\HtmlBuilders\Interfaces\ComponentInterface;
use Apie\TwigTemplateLayoutRenderer\TwigRenderer;
use LogicException;
use ReflectionClass;
use Twig\Extension\AbstractExtension;
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

    public function translate(string|TranslationString $translation): string
    {
        $apieContext = $this->getCurrentContext();
        $translator = $apieContext->getContext(ApieTranslatorInterface::class, false) ?? ApieTranslator::create();
        return $translator->getGeneralTranslation(
            $apieContext,
            $translation instanceof TranslationString
                ? $translation
                : new TranslationString($translation)
        ) ?? $translation;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('component', [$this, 'component'], ['is_safe' => ['all']]),
            new TwigFunction('apieConstant', [$this, 'apieConstant']),
            new TwigFunction('translate', [$this, 'translate'], []),
            new TwigFunction('property', [$this, 'property'], []),
            new TwigFunction('assetUrl', [$this, 'assetUrl'], []),
            new TwigFunction('assetContent', [$this, 'assetContent'], ['is_safe' => ['all']]),
        ];
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
}
