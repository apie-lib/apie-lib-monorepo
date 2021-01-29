<?php


namespace Apie\OpenapiSchema\Spec;

use Apie\CommonValueObjects\Url;
use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\Exceptions\MissingPlaceholderVariables;
use Apie\OpenapiSchema\Map\ServerVariableObjectList;
use Apie\OpenapiSchema\ValueObjects\UrlsWithPlaceholders;
use Apie\ValueObjects\ValueObjectCompareInterface;
use Apie\ValueObjects\ValueObjectInterface;

/**
 * @see https://swagger.io/specification/#server-object
 */
class Server implements ValueObjectInterface
{
    use CompositeValueObjectWithExtension;

    /**
     * @var UrlsWithPlaceholders
     */
    private $url;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var ServerVariableObjectList|null
     */
    private $variables;

    public function __construct(UrlsWithPlaceholders $url)
    {
        $this->url = $url;
        $this->validateProperties();
    }

    public function getUrl(): UrlsWithPlaceholders
    {
        return $this->url;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return ServerVariableObjectList|null
     */
    public function getVariables(): ?ServerVariableObjectList
    {
        return $this->variables;
    }

    protected function validateProperties(): void
    {
        $placeholders = $this->url->getPlaceholders();
        if (!$this->variables && $placeholders) {
            throw new MissingPlaceholderVariables($placeholders);
        }
        $missing = [];
        foreach ($placeholders as $placeholder) {
            if (!isset($this->variables[$placeholder])) {
                $missing[] = $placeholder;
            }
        }
        if (!empty($missing)) {
            throw new MissingPlaceholderVariables($missing);
        }
    }
}