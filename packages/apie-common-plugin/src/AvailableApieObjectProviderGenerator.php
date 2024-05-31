<?php
namespace Apie\ApieCommonPlugin;

use Apie\Core\Other\ActualFileWriter;
use Apie\Core\Other\FileWriterInterface;
use Nette\PhpGenerator\PhpFile;

final class AvailableApieObjectProviderGenerator
{
    public function __construct(
        private readonly FileWriterInterface $fileWriter = new ActualFileWriter()
    ) {
    }

    public function generateFile(array $classNames): void
    {
        $phpCode = $this->generateAvailableApieObjectProvider($classNames);
        $this->fileWriter->writeFile(__DIR__ . '/AvailableApieObjectProvider.php', $phpCode);
        @chmod(__DIR__ . '/AvailableApieObjectProvider.php', 0666);
    }

    private function generateAvailableApieObjectProvider(array $classNames): string
    {
        $phpFile = new PhpFile();
        $namespace = $phpFile->addNamespace('Apie\ApieCommonPlugin');

        $namespace->addUse(ObjectProvider::class);

        $class = $namespace->addClass('AvailableApieObjectProvider');
        $class->setComment('@codeCoverageIgnore' . PHP_EOL . 'This class is auto-generated');

        $class->setExtends(ObjectProvider::class);
        $class->addConstant('DEFINED_CLASSES', $classNames)->setVisibility('protected');
        
        return (string) $phpFile;
    }
}
