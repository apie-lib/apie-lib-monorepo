<?php
namespace Apie\Tests\CmsLayoutUgly;

use Apie\CmsLayoutUgly\UglyDesignSystemLayout;
use Apie\HtmlBuilders\Interfaces\ComponentRendererInterface;
use Apie\HtmlBuilders\TestHelpers\AbstractRenderTestCase;

class UglyDesignSystemLayoutTest extends AbstractRenderTestCase
{
    public function getRenderer(): ComponentRendererInterface
    {
        return UglyDesignSystemLayout::createRenderer();
    }

    public function getFixturesPath(): string
    {
        return  __DIR__ . '/../fixtures';
    }
}
