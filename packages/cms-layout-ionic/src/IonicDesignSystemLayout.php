<?php
namespace Apie\CmsLayoutIonic;

use Apie\HtmlBuilders\Assets\AssetManager;
use Apie\TwigTemplateLayoutRenderer\TwigRenderer;
use Twig\RuntimeLoader\RuntimeLoaderInterface;

final class IonicDesignSystemLayout
{
    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    public static function createRenderer(
        RuntimeLoaderInterface $runtimeLoader,
        ?AssetManager $assetManager = null
    ): TwigRenderer {
        $assetManager ??= new AssetManager();
        return new TwigRenderer(
            __DIR__ . '/../resources/templates',
            $assetManager->withAddedPath(__DIR__ . '/../resources/assets'),
            $runtimeLoader,
            'Apie\HtmlBuilders\Components\\'
        );
    }
}
