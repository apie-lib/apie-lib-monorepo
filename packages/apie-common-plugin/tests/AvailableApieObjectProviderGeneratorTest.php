<?php
namespace Apie\Tests\ApieCommonPlugin;

use Apie\ApieCommonPlugin\AvailableApieObjectProviderGenerator;
use Apie\Core\Other\MockFileWriter;
use PHPUnit\Framework\TestCase;

class AvailableApieObjectProviderGeneratorTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_generate_a_class()
    {
        $fileWriter = new MockFileWriter();
        $testItem = new AvailableApieObjectProviderGenerator($fileWriter);
        $testItem->generateFile(['test']);
        $this->assertCount(1, $fileWriter->writtenFiles);
        $fileContents = reset($fileWriter->writtenFiles);
        $expectedFile = __DIR__ . '/../fixtures/expected-generated-file.phpinc';
        // file_put_contents($expectedFile, $fileContents);
        $expected = file_get_contents($expectedFile);
        $this->assertEquals($expected, $fileContents);
    }
}
