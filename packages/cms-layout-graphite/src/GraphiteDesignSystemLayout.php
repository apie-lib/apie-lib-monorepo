<?php
namespace Apie\CmsLayoutGraphite;

use Apie\HtmlBuilders\Assets\AssetManager;
use Apie\TwigTemplateLayoutRenderer\TwigRenderer;

final class GraphiteDesignSystemLayout
{
    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    public static function createRenderer(?AssetManager $assetManager = null): TwigRenderer
    {
        $assetManager ??= new AssetManager();
        return new TwigRenderer(
            __DIR__ . '/../resources/templates',
            $assetManager->withAddedPath(__DIR__ . '/../resources/assets'),
            'Apie\HtmlBuilders\Components\\'
        );
    }
}
