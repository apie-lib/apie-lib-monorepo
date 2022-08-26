<?php
namespace Apie\Tests\CmsLayoutGraphite;

use Apie\CmsLayoutGraphite\ExampleClass;
use Apie\CmsLayoutGraphite\GraphiteDesignSystemLayout;
use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Lists\ReflectionMethodList;
use Apie\Fixtures\BoundedContextFactory;
use Apie\HtmlBuilders\Components\Dashboard\RawContents;
use Apie\HtmlBuilders\Components\Layout;
use PHPUnit\Framework\TestCase;

class GraphiteDesignSystemLayoutTest extends TestCase
{
    /**
     * @test
     */
    public function it_renders_a_layout()
    {
        $renderer = GraphiteDesignSystemLayout::createRenderer();
        $actual = $renderer->render(
            new Layout(
                'Title',
                new ReflectionMethodList(),
                true,
                new BoundedContextId('default'),
                BoundedContextFactory::createHashmap(),
                new RawContents('<marquee>Hello world</marquee>')
            )
        );
        $fixtureFile = __DIR__ . '/../fixtures/expected-simple-layout.html';
        file_put_contents($fixtureFile, $actual);
        $expected = file_get_contents($fixtureFile);
        $this->assertEquals($expected, $actual);
    }
}