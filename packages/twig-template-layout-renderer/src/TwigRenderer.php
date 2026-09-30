<?php
namespace Apie\TwigTemplateLayoutRenderer;

use Apie\Core\Context\ApieContext;
use Apie\Core\Exceptions\InvalidTypeException;
use Apie\HtmlBuilders\Assets\AssetManager;
use Apie\HtmlBuilders\Interfaces\ComponentInterface;
use Apie\HtmlBuilders\Interfaces\ComponentRendererInterface;
use Apie\TwigTemplateLayoutRenderer\Extension\ComponentHelperExtension;
use Symfony\UX\Icons\Twig\UXIconRuntime;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use WeakMap;

final class TwigRenderer implements ComponentRendererInterface
{
    private Environment $twigEnvironment;

    /** @var WeakMap<UXIconRuntime, ComponentHelperExtension> */
    private static WeakMap $extensions;

    public function __construct(
        string $path,
        private AssetManager $assetManager,
        private string $namespacePrefix,
        private UXIconRuntime $uxIconRuntime,
    ) {
        $loader = new FilesystemLoader([$path, self::getFallbackFixturesPath()]);
        $this->twigEnvironment = new Environment($loader, []);
        self::$extensions ??= new WeakMap();
        $this->twigEnvironment->addExtension(
            self::$extensions[$uxIconRuntime] ??= new ComponentHelperExtension($uxIconRuntime)
        );
    }

    public function getAssetContents(string $filename): string
    {
        return $this->assetManager->getAsset($filename)->getContents();
    }

    public function getAssetUrl(string $filename): string
    {
        return $this->assetManager->getAsset($filename)->getBase64Url();
    }

    public function render(ComponentInterface $component, ApieContext $apieContext): string
    {
        $className = get_class($component);
        if (!str_starts_with($className, $this->namespacePrefix)) {
            throw new InvalidTypeException($component, 'class in ' . $this->namespacePrefix . ' namespace');
        }
        $extension = self::$extensions[$this->uxIconRuntime];
        $extension->selectComponent($this, $component, $apieContext);
        try {
            $templatePath = str_replace('\\', '/', strtolower(substr($className, strlen($this->namespacePrefix)))) . '.html.twig';
            return $this->twigEnvironment->render($templatePath);
        } finally {
            $extension->deselectComponent($component);
        }
    }

    final public static function getFallbackFixturesPath(): string
    {
        return __DIR__ . '/../resources';
    }
}
