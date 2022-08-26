<?php
namespace Apie\CmsLayoutGraphite;

use Apie\Core\Exceptions\InvalidTypeException;
use Apie\HtmlBuilders\Interfaces\ComponentRendererInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class TwigRenderer implements ComponentRendererInterface
{
    private const NAMESPACE = 'Apie\HtmlBuilders\Components\\';

    private Environment $twigEnvironment;

    public function __construct(string $path)
    {
        $loader = new FilesystemLoader($this->path);
        $this->twigEnvironment = new Environment($loader, []);
        $this->twigEnvironment
    }

    public function render(ComponentInterface $component): string
    {
        $className = get_class($component);
        if (!str_starts_with($className, self::NAMESPACE)) {
            throw new InvalidTypeException($component, 'class in ' . self::NAMESPACE . ' namespace');
        }
        $templatePath = str_replace('\\', '/', strtolower(substr($className, strlen(self::NAMESPACE))));

        return $this->twigEnvironment->render($templatePath)
    }
}