<?php
namespace Apie\CommonValueObjects\Bridge\Symfony;

use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;
use Symfony\Component\HtmlSanitizer\Visitor\AttributeSanitizer\AttributeSanitizerInterface;

final class AllowedCssInSpanSanitizer implements AttributeSanitizerInterface
{
    private const ALLOWED_CSS = [
        '/text-decoration-line\s*:\s*underline\s*(;|$)/i' => 'text-decoration-line:underline;',
        '/text-decoration-line\s*:\s*line-through\s*(;|$)/i' => 'text-decoration-line:line-through;',
        '/font-weight\s*:\s*bold\s*(;|$)/i' => 'font-weight:bold;',
        '/font-style\s*:\s*italic\s*(;|$)/i' => 'font-style:italic;',
        '/font-size\s*:\s*x-small\s*(;|$)/i' => 'font-size:x-small;',
        '/font-size\s*:\s*small\s*(;|$)/i' => 'font-size:small;',
        '/font-size\s*:\s*medium\s*(;|$)/i' => 'font-size:medium;',
        '/font-size\s*:\s*large\s*(;|$)/i' => 'font-size:large;',
        '/font-size\s*:\s*x-large\s*(;|$)/i' => 'font-size:x-large;',
        '/font-size\s*:\s*xx-large\s*(;|$)/i' => 'font-size:xx-large;',
        '/font-size\s*:\s*xxx-large\s*(;|$)/i' => 'font-size:xxx-large;',
    ];

    /**
     * @return list<string>
     */
    public function getSupportedElements(): array
    {
        return ['span'];
    }

    /**
     * @return list<string>
     */
    public function getSupportedAttributes(): array
    {
        return ['style'];
    }

    /**
     * Returns the sanitized value of a given attribute for the given element.
     */
    public function sanitizeAttribute(string $element, string $attribute, string $value, HtmlSanitizerConfig $config): ?string
    {
        $newValue = '';
        $found = true;
        while ($found) {
            $found = false;
            $value = preg_replace_callback(
                '/(;|^)\s*(?<color>(background-|)color\s*:[^;]*(;|$))/i',
                function (array $matches) use (&$newValue, &$found): string {
                    $newValue .= $matches['color'];
                    $found = true;
                    return '';
                },
                $value
            );
        }
        foreach (self::ALLOWED_CSS as $regex => $css) {
            if (preg_match($regex, $value)) {
                $newValue .= $css;
            }
        }

        return $newValue ? rtrim($newValue, ';') : null;
    }
}
