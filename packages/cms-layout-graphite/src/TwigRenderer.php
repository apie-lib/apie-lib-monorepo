<?php
namespace Apie\CmsLayoutGraphite;

use Apie\CmsLayoutGraphite\Extension\ComponentHelperExtension;
use Apie\Core\Exceptions\InvalidTypeException;
use Apie\HtmlBuilders\Assets\AssetManager;
use Apie\HtmlBuilders\Interfaces\ComponentInterface;
use Apie\HtmlBuilders\Interfaces\ComponentRendererInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class TwigRenderer implements ComponentRendererInterface
{
    private const NAMESPACE = 'Apie\HtmlBuilders\Components\\';

    private Environment $twigEnvironment;

    private static ComponentHelperExtension $extension;

    public function __construct(string $path, private AssetManager $assetManager)
    {
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

    public function render(ComponentInterface $component): string
    {
        $className = get_class($component);
        if (!str_starts_with($className, self::NAMESPACE)) {
            throw new InvalidTypeException($component, 'class in ' . self::NAMESPACE . ' namespace');
        }
        self::$extension->selectComponent($this, $component);
        try {
            $templatePath = str_replace('\\', '/', strtolower(substr($className, strlen(self::NAMESPACE)))) . '.html.twig';

            return $this->twigEnvironment->render($templatePath);
        } finally {
            self::$extension->deselectComponent($component);
        }
    }
}
