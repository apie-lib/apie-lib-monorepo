<?php


namespace Apie\StaticConfigPlugin;

use Apie\Core\PluginInterfaces\ApieConfigInterface;

class StaticConfigPlugin implements ApieConfigInterface
{
    private $baseUrl;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }
}
