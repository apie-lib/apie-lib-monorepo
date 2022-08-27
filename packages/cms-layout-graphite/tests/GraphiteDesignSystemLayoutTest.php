<?php
namespace Apie\Tests\CmsLayoutGraphite;

use Apie\ApieBundle\Wrappers\BoundedContextHashmapFactory;
use Apie\CmsLayoutGraphite\ExampleClass;
use Apie\CmsLayoutGraphite\GraphiteDesignSystemLayout;
use Apie\Core\BoundedContext\BoundedContextHashmap;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Context\ApieContext;
use Apie\Core\Lists\ReflectionMethodList;
use Apie\Fixtures\BoundedContextFactory;
use Apie\HtmlBuilders\Components\Dashboard\RawContents;
use Apie\HtmlBuilders\Components\Layout;
use Apie\HtmlBuilders\Configuration\CurrentConfiguration;
use Apie\HtmlBuilders\Interfaces\ComponentRendererInterface;
use Apie\HtmlBuilders\TestHelpers\AbstractRenderTest;
use PHPUnit\Framework\TestCase;

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