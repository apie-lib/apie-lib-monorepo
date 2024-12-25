<?php
namespace Apie\Tests\TwigTemplateLayoutRenderer\Command;

use Apie\Core\Other\MockFileWriter;
use Apie\TwigTemplateLayoutRenderer\Command\CreateCustomLayoutRendererCommand;
use Apie\TwigTemplateLayoutRenderer\Skeleton\ClassCodeGenerator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CreateCustomLayoutRendererCommandTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_create_a_layout_package()
    {
        $filewriter = new MockFileWriter();
        $testItem = new CreateCustomLayoutRendererCommand(
            $filewriter,
            new ClassCodeGenerator()
        );

        $commandTester = new CommandTester($testItem);
        $commandTester->execute([
            'name' => 'usedname',
            'frontend-path' => '/tmp/front',
            'backend-path' => '/tmp/backend',
            'backend-namespace' => 'App\Example',
        ]);
        $commandTester->assertCommandIsSuccessful();
        foreach ($filewriter->writtenFiles as $filename => $contents) {
            $this->assertDoesNotMatchRegularExpression(
                '/skeleton/i',
                $contents,
                'File ' . $filename . ' does not contain the skeleton placeholder'
            );
        }
        $this->assertArrayHasKey('/tmp/front/.github/workflows/npm-publish.yml', $filewriter->writtenFiles, 'hidden files are synced too');
        $this->assertArrayHasKey('/tmp/front/.gitignore', $filewriter->writtenFiles, 'hidden files are synced too');
    }
}
