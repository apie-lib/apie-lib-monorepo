<?php
namespace Apie\CmsLayoutGraphite;

use Apie\HtmlBuilders\Assets\AssetManager;
use Apie\TwigTemplateLayoutRenderer\TwigRenderer;
use Symfony\UX\Icons\Twig\UXIconRuntime;

final class GraphiteDesignSystemLayout
{
    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    public static function createRenderer(
        UXIconRuntime $uxIconRuntime,
        ?AssetManager $assetManager = null
    ): TwigRenderer {
        $assetManager ??= new AssetManager();
        return new TwigRenderer(
            __DIR__ . '/../resources/templates',
            $assetManager->withAddedPath(__DIR__ . '/../resources/assets'),
            'Apie\HtmlBuilders\Components\\',
            $uxIconRuntime
        );
    }
}
