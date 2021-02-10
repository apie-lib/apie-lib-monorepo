<?php


namespace Apie\OpenapiSchema\Spec;

use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\Map\MediaTypeMap;
use Apie\ValueObjects\ValueObjectInterface;

class RequestBody implements ValueObjectInterface
{
    use CompositeValueObjectWithExtension;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var MediaTypeMap
     */
    private $content;

    /**
     * @var bool|null
     */
    private $required = false;

    public function __construct(MediaTypeMap $content)
    {
        $this->content = $content;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return MediaTypeMap
     */
    public function getContent(): MediaTypeMap
    {
        return $this->content;
    }

    /**
     * @return bool|null
     */
    public function isRequired(): ?bool
    {
        return $this->required ?? false;
    }
}
