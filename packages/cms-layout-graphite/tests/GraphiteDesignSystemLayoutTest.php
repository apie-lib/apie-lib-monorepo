<?php
namespace Apie\Tests\CmsLayoutGraphite;

use Apie\CmsLayoutGraphite\GraphiteDesignSystemLayout;
use Apie\HtmlBuilders\Interfaces\ComponentRendererInterface;
use Apie\HtmlBuilders\TestHelpers\AbstractRenderTest;

class GraphiteDesignSystemLayoutTest extends AbstractRenderTest
{
    public function getRenderer(): ComponentRendererInterface
    {
        return GraphiteDesignSystemLayout::createRenderer();
    }

    public function getFixturesPath(): string
    {
        return  __DIR__ . '/../fixtures';
    }
}
