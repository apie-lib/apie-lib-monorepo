<?php
namespace Apie\HtmlBuilders\Configuration;

class ApplicationConfiguration {
    private array $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function getBrowserTitle(string $pageTitle): string
    {
        return sprintf((string) ($this->config['head']['title-format'] ?? 'Apie CMS - %s'), $pageTitle);
    }

    public function shouldDisplayBoundedContextSelect(): bool
    {
        return filter_var($this->config['application']['bounded-context-select'] ?? true, FILTER_VALIDATE_BOOL);
    }
}