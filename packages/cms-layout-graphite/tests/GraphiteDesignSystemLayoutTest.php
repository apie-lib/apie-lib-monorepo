<?php
namespace Apie\Tests\CmsLayoutGraphite;

use Apie\CmsLayoutGraphite\GraphiteDesignSystemLayout;
use Apie\HtmlBuilders\Interfaces\ComponentRendererInterface;
use Apie\HtmlBuilders\TestHelpers\AbstractRenderTestCase;

class GraphiteDesignSystemLayoutTest extends AbstractRenderTestCase
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
