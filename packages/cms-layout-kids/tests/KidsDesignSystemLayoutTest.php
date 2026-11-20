<?php
namespace Apie\Tests\CmsLayoutKids;

use Apie\CmsLayoutKids\KidsDesignSystemLayout;
use Apie\HtmlBuilders\Interfaces\ComponentRendererInterface;
use Apie\HtmlBuilders\TestHelpers\AbstractRenderTestCase;

class KidsDesignSystemLayoutTest extends AbstractRenderTestCase
{
    public function getRenderer(): ComponentRendererInterface
    {
        return KidsDesignSystemLayout::createRenderer(
            self::createTwigRuntimeForTests()
        );
    }

    public function getFixturesPath(): string
    {
        return  __DIR__ . '/../fixtures';
    }
}
