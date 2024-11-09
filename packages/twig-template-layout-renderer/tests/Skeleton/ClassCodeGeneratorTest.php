<?php
namespace Apie\Tests\TwigTemplateLayoutRenderer\Skeleton;

use Apie\Common\ValueObjects\EntityNamespace;
use Apie\Core\Identifiers\Identifier;
use Apie\TwigTemplateLayoutRenderer\Skeleton\ClassCodeGenerator;
use PHPUnit\Framework\TestCase;

class ClassCodeGeneratorTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_generate_a_composer_file()
    {
        $testItem = new ClassCodeGenerator();
        $actual = $testItem->generateComposerJsonFile(
            new Identifier('test'),
            new EntityNamespace('Example\\Namespace'),
            '1.2.3'
        );
        $expectedFile = __DIR__ . '/../../fixtures/expected-composer-json.json';
        // file_put_contents($expectedFile, $actual);
        $this->assertEquals(file_get_contents($expectedFile), $actual);
    }

    /**
     * @test
     */
    public function it_can_generate_a_layout_file()
    {
        $testItem = new ClassCodeGenerator();
        $actual = $testItem->generateLayoutClass(
            new Identifier('test'),
            new EntityNamespace('Example\\Namespace'),
            '1.2.3'
        );
        $expectedFile = __DIR__ . '/../../fixtures/expected-layout.phpinc';
        // file_put_contents($expectedFile, $actual);
        $this->assertEquals(file_get_contents($expectedFile), $actual);
    }

    /**
     * @test
     */
    public function it_can_generate_a_layout_test_file()
    {
        $testItem = new ClassCodeGenerator();
        $actual = $testItem->generateLayoutTestClass(
            new Identifier('test'),
            new EntityNamespace('Example\\Namespace'),
            '1.2.3'
        );
        $expectedFile = __DIR__ . '/../../fixtures/expected-layout-test.phpinc';
        // file_put_contents($expectedFile, $actual);
        $this->assertEquals(file_get_contents($expectedFile), $actual);
    }
}
