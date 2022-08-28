<?php
namespace Apie\CmsLayoutGraphite\Extension;

use Apie\CmsLayoutGraphite\TwigRenderer;
use Apie\HtmlBuilders\Interfaces\ComponentInterface;
use LogicException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ComponentHelperExtension extends AbstractExtension
{
    /** @var ComponentInterface[] */
    private array $componentsHandled = [];

    /** @var TwigRenderer[] */
    private array $renderers = [];

    public function selectComponent(TwigRenderer $renderer, ComponentInterface $component)
    {
        $this->renderers[] = $renderer;
        $this->componentsHandled[] = $component;
    }

    public function deselectComponent(ComponentInterface $component)
    {
        if (end($this->componentsHandled) !== $component) {
            throw new LogicException('Last component is not the one being deselected');
        }
        array_pop($this->componentsHandled);
        array_pop($this->renderers);
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('component', [$this, 'component'], ['is_safe' => ['all']]),
            new TwigFunction('property', [$this, 'property'], []),
            new TwigFunction('assetUrl', [$this, 'assetUrl'], []),
            new TwigFunction('assetContent', [$this, 'assetContent'], []),
        ];
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
        return $this->getCurrentRenderer()->render($this->getCurrentComponent()->getComponent($componentName));
    }
}
