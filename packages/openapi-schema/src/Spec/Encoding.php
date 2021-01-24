<?php


namespace Apie\OpenapiSchema\Spec;


use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\Map\HeaderMap;
use Apie\ValueObjects\ValueObjectCompareInterface;
use Apie\ValueObjects\ValueObjectInterface;

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