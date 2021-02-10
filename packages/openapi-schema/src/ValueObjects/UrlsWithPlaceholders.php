<?php


namespace Apie\OpenapiSchema\ValueObjects;

use Apie\CommonValueObjects\Url;
use Apie\ValueObjects\StringTrait;
use Apie\ValueObjects\ValueObjectInterface;

class UrlsWithPlaceholders implements ValueObjectInterface
{
    use StringTrait;

    /**
     * @var string[]|null
     */
    private $placeholders;

    protected function validValue(string $value): bool
    {
        $identifier = '(\w|-|{\w+})+';
        $scheme = '^' . $identifier . '://';
        $username = $identifier . '(:' . $identifier . ')?';
        $hostname = $identifier . '(\.' . $identifier . ')*';
        $path = '/(.|{\w+})*';
        $regex = '#' . $scheme . '(' . $username . '@)?' . $hostname . '(' . $path . ')*$#i';
        return preg_match($regex, $value) ? true : false;
    }

    public function getPlaceholders()
    {
        if (null !== $this->placeholders) {
            return $this->placeholders;
        }
        if (preg_match_all('/{(\w+)}/', $this->value, $matches)) {
            return $this->placeholders = $matches[1];
        }
        return $this->placeholders = [];
    }

    public function replace(array $replacements): Url
    {
        $value = $this->value;
        foreach ($this->getPlaceholders() as $placeholder) {
            $value = str_replace('{' . $placeholder . '}', rawurlencode($replacements[$placeholder]), $value);
        }
        return new Url($value);
    }

    protected function sanitizeValue(string $value): string
    {
        return $value;
    }
}
