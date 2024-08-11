<?php
namespace Apie\Maker\BoundedContext\Services;

use Apie\Core\Other\FileWriterInterface;
use Apie\Maker\Enums\OverwriteStrategy;

final class CodeWriter
{
    public function __construct(private readonly FileWriterInterface $fileWriter)
    {
    }
    public function startWriting(string $targetPath, OverwriteStrategy $strategy): void
    {
        switch ($strategy) {
            case OverwriteStrategy::Reset:
                $this->fileWriter->clearPath($targetPath);
        }
    }
}
