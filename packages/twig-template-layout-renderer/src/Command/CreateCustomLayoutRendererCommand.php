<?php
namespace Apie\TwigTemplateLayoutRenderer\Command;

use Apie\Common\ValueObjects\EntityNamespace;
use Apie\Core\ApieLib;
use Apie\Core\Identifiers\Identifier;
use Apie\Core\Other\FileWriterInterface;
use Apie\TwigTemplateLayoutRenderer\Skeleton\ClassCodeGenerator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

#[AsCommand('apie:cms:create-custom-layout', 'create frontend and backend code for a custom layout')]
class CreateCustomLayoutRendererCommand extends Command
{
    private const PADDING = 80;

    public function __construct(
        private readonly FileWriterInterface $filewriter,
        private readonly ClassCodeGenerator $classCodeGenerator
    ) {
        parent::__construct();
    }
    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'name of custom layout');
        $this->addArgument('backend-path', InputArgument::REQUIRED, 'path of backend (PHP) code');
        $this->addArgument('backend-namespace', InputArgument::REQUIRED);
        $this->addArgument('frontend-path', InputArgument::REQUIRED, 'path of frontend code');
        $this->addOption(
            'apie-version',
            null,
            InputOption::VALUE_REQUIRED,
            'used apie version constraint',
            ApieLib::VERSION
        );
    }

    private function createBackendPath(
        Identifier $name,
        string $backendPath,
        EntityNamespace $entityNamespace,
        string $apieVersion,
        OutputInterface $output
    ): void {
        $targetPath = $backendPath . 'src/' . $name->toPascalCaseSlug() . 'DesignSystemLayout.php';
        $layoutClassCode = $this->classCodeGenerator->generateLayoutClass($name, $entityNamespace);
        $output->write(str_pad($targetPath, self::PADDING, ' ', STR_PAD_RIGHT));
        $output->writeln('(' . strlen($layoutClassCode) . ' bytes)');
        $this->filewriter->writeFile(
            $targetPath,
            $layoutClassCode
        );
        $targetPath = $backendPath . 'tests/' . $name->toPascalCaseSlug() . 'DesignSystemLayoutTest.php';
        $layoutClassCode = $this->classCodeGenerator->generateLayoutTestClass($name, $entityNamespace);
        $output->write(str_pad($targetPath, self::PADDING, ' ', STR_PAD_RIGHT));
        $output->writeln('(' . strlen($layoutClassCode) . ' bytes)');
        $this->filewriter->writeFile(
            $targetPath,
            $layoutClassCode
        );
        $targetPath = $backendPath . 'composer.json';
        $composerJsonContents = $this->classCodeGenerator->generateComposerJsonFile(
            $name,
            $entityNamespace,
            $apieVersion
        );
        $output->write(str_pad($targetPath, self::PADDING, ' ', STR_PAD_RIGHT));
        $output->writeln('(' . strlen($composerJsonContents) . ' bytes)');
        $this->filewriter->writeFile(
            $targetPath,
            $composerJsonContents
        );
        $search = [
            'skeleton',
            'Skeleton',
        ];
        $replace = [
            $name->toNative(),
            $name->toPascalCaseSlug()->toNative(),
        ];
        $this->syncFiles(
            __DIR__ . '/../../scaffolding/backend',
            $backendPath,
            $output,
            $search,
            $replace
        );
    }

    private function createFrontendPath(
        Identifier $name,
        string $frontendPath,
        OutputInterface $output
    ): void {
        $search = [
            'skeleton',
            'Skeleton',
        ];
        $replace = [
            $name->toNative(),
            $name->toPascalCaseSlug()->toNative(),
        ];
        $this->syncFiles(
            __DIR__ . '/../../scaffolding/frontend',
            $frontendPath,
            $output,
            $search,
            $replace
        );
    }

    private function syncFiles(
        string $inputPath,
        string $outputPath,
        OutputInterface $output,
        array $search,
        array $replace
    ): void {
        $files = Finder::create()
            ->files()
            ->in($inputPath)
            ->ignoreDotFiles(false)
            ->ignoreVCS(false);
        foreach ($files as $file) {
            $contents = str_replace($search, $replace, $file->getContents());
            $targetPath = str_replace($search, $replace, $outputPath . $file->getRelativePathname());
            $output->write(str_pad($targetPath, self::PADDING, ' ', STR_PAD_RIGHT));
            $output->writeln('(' . strlen($contents) . ' bytes)');
            $this->filewriter->writeFile($targetPath, $contents);
        }
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->createFrontendPath(
            new Identifier($input->getArgument('name')),
            rtrim($input->getArgument('frontend-path'), '/') . '/',
            $output
        );

        $this->createBackendPath(
            new Identifier($input->getArgument('name')),
            rtrim($input->getArgument('backend-path'), '/') . '/',
            new EntityNamespace($input->getArgument('backend-namespace')),
            $input->getOption('apie-version'),
            $output
        );

        return Command::SUCCESS;
    }
}
