<?php
namespace Apie\TwigTemplateLayoutRenderer;

use Apie\Core\Context\ApieContext;
use Apie\Core\Exceptions\InvalidTypeException;
use Apie\HtmlBuilders\Assets\AssetManager;
use Apie\HtmlBuilders\Interfaces\ComponentInterface;
use Apie\HtmlBuilders\Interfaces\ComponentRendererInterface;
use Apie\TwigTemplateLayoutRenderer\Extension\ComponentHelperExtension;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class TwigRenderer implements ComponentRendererInterface
{
    private Environment $twigEnvironment;

    private static ComponentHelperExtension $extension;

    public function __construct(
        string $path,
        private AssetManager $assetManager,
        private string $namespacePrefix
    ) {
        $loader = new FilesystemLoader($path);
        $this->twigEnvironment = new Environment($loader, []);
        if (!isset(self::$extension)) {
            self::$extension = new ComponentHelperExtension();
        }
        $this->twigEnvironment->addExtension(self::$extension);
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
        self::$extension->selectComponent($this, $component, $apieContext);
        try {
            $templatePath = str_replace('\\', '/', strtolower(substr($className, strlen($this->namespacePrefix)))) . '.html.twig';
            return $this->twigEnvironment->render($templatePath);
        } finally {
            self::$extension->deselectComponent($component);
        }
    }
}
