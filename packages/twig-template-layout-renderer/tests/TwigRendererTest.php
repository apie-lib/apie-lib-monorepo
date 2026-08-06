<?php
namespace Apie\Tests\TwigTemplateLayoutRenderer;

use Apie\Core\Context\ApieContext;
use Apie\Core\Exceptions\InvalidTypeException;
use Apie\HtmlBuilders\Assets\AssetManager;
use Apie\Tests\TwigTemplateLayoutRenderer\Fixtures\Dummy;
use Apie\Tests\TwigTemplateLayoutRenderer\Fixtures\MissingTemplate;
use Apie\TwigTemplateLayoutRenderer\TwigRenderer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;
use Twig\Error\LoaderError;
use Twig\RuntimeLoader\ContainerRuntimeLoader;

class TwigRendererTest extends TestCase
{

    private function given_a_twig_renderer(): TwigRenderer
    {
        $container = new Container();
        return new TwigRenderer(
            __DIR__ . '/../fixtures',
            new AssetManager(__DIR__ . '/../fixtures/assets'),
            new ContainerRuntimeLoader($container),
            'Apie\Tests\TwigTemplateLayoutRenderer\Fixtures\\'
        );
    }
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_render_a_twig_template_from_a_component()
    {
        $testItem = $this->given_a_twig_renderer();
        $actual = $testItem->render(new Dummy('world', new Dummy('world 2')), new ApieContext());
        $expectedFilePath = __DIR__ . '/../fixtures/expected-dummy.html';
        // file_put_contents($expectedFilePath, $actual);
        $expected = file_get_contents($expectedFilePath);
        $this->assertEquals($expected, $actual);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_throws_error_on_invalid_namespace_for_component()
    {
        $container = new Container();
        $testItem = new TwigRenderer(
            __DIR__,
            new AssetManager(),
            new ContainerRuntimeLoader($container),
            'Poop\\'
        );
        $this->expectException(InvalidTypeException::class);
        $this->expectExceptionMessage('class in Poop\\ namespace');
        $testItem->render(new Dummy('world', new Dummy('world 2')), new ApieContext());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_throws_error_on_missing_template_for_component()
    {
        $testItem = $this->given_a_twig_renderer();
        $this->expectException(LoaderError::class);
        $this->expectExceptionMessage('Unable to find template "missingtemplate.html.twig"');
        $testItem->render(new MissingTemplate([]), new ApieContext());
    }
}
