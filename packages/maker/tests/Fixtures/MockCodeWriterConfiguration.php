<?php
namespace Apie\Tests\Maker\Fixtures;

use Apie\Maker\BoundedContext\Interfaces\CodeWriterConfigurationInterface;
use Apie\Maker\Enums\OverwriteStrategy;

class MockCodeWriterConfiguration implements CodeWriterConfigurationInterface
{
    public function __construct(
        public OverwriteStrategy $strategy,
        public string $targetPath,
        public string $targetNamespace
    ) {
    }

    public function getOverwriteStrategy(): OverwriteStrategy
    {
        return $this->strategy;
    }
    public function getTargetPath(): string
    {
        return $this->targetPath;
    }
    public function getTargetNamespace(string $sub = ''): ?string
    {
        if ($sub === '') {
            return rtrim($this->targetNamespace, '\\');
        }
        return rtrim($this->targetNamespace, '\\') . '\\' . ltrim($sub, '\\');
    }
}
