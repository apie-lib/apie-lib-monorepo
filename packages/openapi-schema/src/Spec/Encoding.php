<?php


namespace Apie\OpenapiSchema\Spec;

use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\Map\HeaderMap;
use Apie\ValueObjects\ValueObjectInterface;

/**
 * @see https://swagger.io/specification/#encoding-object
 */
class Encoding implements ValueObjectInterface
{
    use CompositeValueObjectWithExtension;

    /**
     * @var string|null
     */
    private $contentType;

    /**
     * @var HeaderMap|null
     */
    private $headers;

    /**
     * @var string|null
     */
    private $style;

    /**
     * @var bool|null
     */
    private $explode;

    /**
     * @var bool|null
     */
    private $allowReserved;
}
