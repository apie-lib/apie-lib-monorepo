<?php
namespace Apie\Tests\CmsLayoutIonic;

use Apie\CmsLayoutIonic\IonicDesignSystemLayout;
use Apie\HtmlBuilders\Interfaces\ComponentRendererInterface;
use Apie\HtmlBuilders\TestHelpers\AbstractRenderTestCase;

class IonicDesignSystemLayoutTest extends AbstractRenderTestCase
{
    public function getRenderer(): ComponentRendererInterface
    {
        return IonicDesignSystemLayout::createRenderer();
    }

    public function getFixturesPath(): string
    {
        return  __DIR__ . '/../fixtures';
    }
}
