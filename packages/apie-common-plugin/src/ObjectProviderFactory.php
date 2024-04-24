<?php
namespace Apie\ApieCommonPlugin;

use Apie\Core\Other\ActualFileWriter;
use Apie\Core\Other\FileWriterInterface;
use Symfony\Component\Finder\Finder;

final class ObjectProviderFactory
{
    private function __construct()
    {
    }

    public static function create(FileWriterInterface $fileWriter = new ActualFileWriter()): ObjectProvider
    {
        if (class_exists(AvailableApieObjectProvider::class)) {
            return new AvailableApieObjectProvider();
        }
        $classNames = [];
        foreach (Finder::create()->in(__DIR__ . '/../../')->files()->name('composer.json')->depth([1]) as $file) {
            $contents = json_decode(file_get_contents((string) $file), true);
            if (is_array($contents['extra']['apie-objects'] ?? null)) {
                $classNames = [...$classNames, ...$contents['extra']['apie-objects']];
            }
        }
        $code = new AvailableApieObjectProviderGenerator($fileWriter);
        $code->generateFile($classNames);
        if (class_exists(AvailableApieObjectProvider::class)) {
            return new AvailableApieObjectProvider();
        }
        return new class extends ObjectProvider {};
    }
}
