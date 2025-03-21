<?php
namespace Apie\Tests\Cms;

use Apie\Cms\Controllers\DashboardController;
use Apie\Cms\Services\ResponseFactory;
use Apie\Common\ActionDefinitionProvider;
use Apie\Common\Events\ResponseDispatcher;
use Apie\Core\Context\ApieContext;
use Apie\Core\ContextBuilders\ContextBuilderFactory;
use Apie\Fixtures\BoundedContextFactory;
use Apie\HtmlBuilders\Components\Layout;
use Apie\HtmlBuilders\Configuration\ApplicationConfiguration;
use Apie\HtmlBuilders\Factories\ComponentFactory;
use Apie\HtmlBuilders\Factories\FieldDisplayComponentFactory;
use Apie\HtmlBuilders\Factories\FormComponentFactory;
use Apie\HtmlBuilders\Factories\ResourceActionFactory;
use Apie\HtmlBuilders\Interfaces\ComponentRendererInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class DashboardControllerTest extends TestCase
{
    use ProphecyTrait;

    protected function givenAGetRequest(string $uri): ServerRequestInterface
    {
        $factory = new Psr17Factory();
        return $factory->createServerRequest('GET', $uri)
            ->withHeader('Accept', 'application/json')
            ->withAttribute('boundedContextId', 'default');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_generates_html()
    {
        $renderer = $this->prophesize(ComponentRendererInterface::class);
        $renderer->render(Argument::type(Layout::class), Argument::type(ApieContext::class))
            ->shouldBeCalled()
            ->willReturn('<html></html>');
        $testItem = new DashboardController(
            new ComponentFactory(
                new ApplicationConfiguration(),
                BoundedContextFactory::createHashmap(),
                FormComponentFactory::create(),
                FieldDisplayComponentFactory::create([]),
                new ResourceActionFactory(new ActionDefinitionProvider())
            ),
            new ContextBuilderFactory(),
            new ResponseFactory(
                $renderer->reveal(),
                new ResponseDispatcher(new EventDispatcher())
            )
        );
        $request = $this->givenAGetRequest('/');
        $response = $testItem($request);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
