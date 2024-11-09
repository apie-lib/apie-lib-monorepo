<?php
namespace Apie\Tests\TwigTemplateLayoutRenderer\Command;

use Apie\Core\Other\ActualFileWriter;
use Apie\HtmlBuilders\Interfaces\ComponentRendererInterface;
use Apie\HtmlBuilders\TestHelpers\AbstractRenderTestCase;
use Apie\TwigTemplateLayoutRenderer\Command\CreateCustomLayoutRendererCommand;
use Apie\TwigTemplateLayoutRenderer\Skeleton\ClassCodeGenerator;
use Symfony\Component\Console\Tester\CommandTester;

class CreateLayoutIntegrationTest extends AbstractRenderTestCase
{
    private const CLASSNAME = 'Generated\Usedname\UsednameDesignSystemLayout';
    private static string $path;
    private static ComponentRendererInterface $renderer;

    public function __destruct()
    {
        if (isset(self::$path) && self::$path !== '/') {
            system('rm -rf ' . escapeshellarg(self::$path));
        }
    }

    public function getRenderer(): ComponentRendererInterface
    {
        if (!isset(self::$renderer)) {
            if (!class_exists(self::CLASSNAME)) {
                $renderer = new CreateCustomLayoutRendererCommand(
                    new ActualFileWriter(),
                    new ClassCodeGenerator()
                );
                self::$path = sys_get_temp_dir() . '/' . uniqid('create-custom');
                $commandTester = new CommandTester($renderer);
                $commandTester->execute([
                    'name' => 'usedname',
                    'frontend-path' => self::$path . '/front',
                    'backend-path' => self::$path . '/backend',
                    'backend-namespace' => 'Generated\Usedname',
                ]);
                $commandTester->assertCommandIsSuccessful();
                require_once(self::$path . '/backend/src/UsednameDesignSystemLayout.php');
            }
            self::assertTrue(class_exists(self::CLASSNAME));
            return self::$renderer = self::CLASSNAME::createRenderer();
        }
        return self::$renderer;
    }

    public function getFixturesPath(): string
    {
        return __DIR__ . '/../../fixtures/generated';
    }
}