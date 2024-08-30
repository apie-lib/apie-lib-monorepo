<?php
namespace App\ApiePlayground\Example\Enums;

use Apie\CmsLayoutGraphite\GraphiteDesignSystemLayout;
use Apie\CmsLayoutIonic\IonicDesignSystemLayout;
use Apie\HtmlBuilders\Assets\AssetManager;
use Apie\HtmlBuilders\Interfaces\ComponentRendererInterface;

enum SelectedLayout: string {
    case Graphite = GraphiteDesignSystemLayout::class;
    case Ionic = IonicDesignSystemLayout::class;

    public static function fromConfig(array $config): self
    {
        return self::tryFrom(
            $config['services'][ComponentRendererInterface::class]['factory'][0] ?? IonicDesignSystemLayout::class
        ) ?? self::Ionic;
    }

    public function toServiceDefinition(): array
    {
        return [
            'factory' => [$this->value, 'createRenderer'],
            'arguments' => ['@' . AssetManager::class]
        ];
    }
}