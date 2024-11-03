<?php
namespace Apie\TwigTemplateLayoutRenderer\Command;

use Apie\Core\Other\FileWriterInterface;
use Composer\Console\Input\InputArgument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

#[AsCommand('apie:cms:create-custom-layout', 'create frontend and backend code for a custom layout')]
class CreateCustomLayoutRendererCommand extends Command
{
    public function __construct(private readonly FileWriterInterface $filewriter)
    {
        parent::__construct();
    }
    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'name of custom layout');
        $this->addArgument('backend-path', InputArgument::REQUIRED, 'path of backend (PHP) code');
        $this->addArgument('backend-namespace', InputArgument::REQUIRED);
        $this->addArgument('frontend-path', InputArgument::REQUIRED, 'path of frontend code');
    }

    private function createFrontendPath(string $frontendPath, OutputInterface $output)
    {
        foreach (Finder::create()->files()->in(__DIR__ . '/../../scaffolding')->ignoreVCS(false) as $file) {
            $contents = $file->getContents();
            $targetPath = $frontendPath . $file->getRelativePath();
            $output->write(str_pad($targetPath, 32, ' ', STR_PAD_RIGHT));
            $output->writeln('(' . strlen($contents) . ' bytes)');
            $this->filewriter->writeFile($targetPath, $contents);
        }
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->createFrontendPath($input->getArgument('frontend-path'), $output);
        return Command::SUCCESS;
    }
}