<?php
namespace Apie\Maker\BoundedContext\Interfaces;

use Apie\Maker\Enums\OverwriteStrategy;

interface CodeWriterConfigurationInterface
{
    public function getOverwriteStrategy(): OverwriteStrategy;
    public function getTargetPath(): string|false|null;
    public function getTargetNamespace(string $sub = ''): ?string;
}
