<?php
namespace Apie\CommonValueObjects\Bridge\Symfony;

use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;
use Symfony\Component\HtmlSanitizer\Visitor\AttributeSanitizer\AttributeSanitizerInterface;

class YoutubeNoCookieSanitizer implements AttributeSanitizerInterface
{
    /**
     * @return list<string>
     */
    public function getSupportedElements(): array
    {
        return ['iframe'];
    }

    /**
     * @return list<string>
     */
    public function getSupportedAttributes(): array
    {
        return ['src'];
    }

    public function sanitizeAttribute(string $element, string $attribute, string $value, HtmlSanitizerConfig $config): ?string
    {
        return preg_replace('/^(https?:)?\/\/www\.youtube\.com\/embed\//', 'https://www.youtube-nocookie.com/embed/', $value);
    }
}
