<?php
namespace Apie\TwigTemplateLayoutRenderer\Skeleton;

use Apie\Common\ValueObjects\EntityNamespace;
use Apie\Core\ApieLib;
use Apie\Core\Identifiers\Identifier;

final class ClassCodeGenerator
{
    public function generateComposerJsonFile(
        Identifier $name,
        EntityNamespace $entityNamespace,
        string $apieVersion = ApieLib::VERSION
    ): string {
        $contents = [
            "name" => "apie/cms-layout-" . $name,
            "description" => "Composer package of the apie library: cms layout " . $name,
            "type" => "library",
            "license" => "MIT",
            "authors" => [
            ],
            "autoload" => [
                "psr-4" => [
                    $entityNamespace->toNative() => "src/",
                ]
            ],
            "autoload-dev" => [
                "psr-4" => [
                    $entityNamespace->toTestNamespace()->toNative() => 'tests/',
                ]
            ],
            "require" => [
                "php" => ">=8.3",
                "apie/core" => $apieVersion,
                "apie/html-builders" => $apieVersion,
                "apie/twig-template-layout-renderer" => $apieVersion,
                "twig/twig" => "^3.10.2"
            ],
            "require-dev" => [
                "apie/fixtures" => $apieVersion,
                "illuminate/support" => "*",
                "phpunit/phpunit" => "^9.6.19"
            ],
            "minimum-stability" => "dev",
            "prefer-stable" => true
        ];
        return json_encode($contents, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function generateLayoutClass(
        Identifier $name,
        EntityNamespace $entityNamespace
    ): string {
        $contents = '<?php' . PHP_EOL;
        $contents .= 'namespace ' . rtrim($entityNamespace->toNative(), '\\') . ';' . PHP_EOL;
        $contents .= '        
use Apie\HtmlBuilders\Assets\AssetManager;
use Apie\TwigTemplateLayoutRenderer\TwigRenderer;

';
        $contents .= 'class ' . $name->toPascalCaseSlug()-> toNative() . 'DesignSystemLayout
{
    /** 
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    public static function createRenderer(?AssetManager $assetManager = null): TwigRenderer
    {
        $assetManager ??= new AssetManager();
        return new TwigRenderer(
            __DIR__ . \'/../resources/templates\',
            $assetManager->withAddedPath(__DIR__ . \'/../resources/assets\'),
            "Apie\HtmlBuilders\Components\\\\"
        );
    }
}
';
        return $contents;
    }

    public function generateLayoutTestClass(
        Identifier $name,
        EntityNamespace $entityNamespace
    ): string {
        $contents = '<?php' . PHP_EOL;
        $contents .= 'namespace ' . rtrim($entityNamespace->toTestNamespace()->toNative(), '\\') . ';' . PHP_EOL;
        $contents .= '
use ' . $entityNamespace . $name->toPascalCaseSlug()->toNative() . 'DesignSystemLayout;
use Apie\HtmlBuilders\Interfaces\ComponentRendererInterface;
use Apie\HtmlBuilders\TestHelpers\AbstractRenderTestCase;

';
        $contents .= 'class ' . $name->toPascalCaseSlug()-> toNative() . 'DesignSystemLayoutTest extends AbstractRenderTestCase
{ 
    public function getRenderer(): ComponentRendererInterface
    {
        return ' . $name->toPascalCaseSlug()-> toNative() . 'DesignSystemLayout::createRenderer();
    }

    public function getFixturesPath(): string
    {
        return  __DIR__ . \'/../fixtures\';
    }
}
';
        return $contents;
    }
}
