<?php


namespace Apie\OpenapiSchema\Spec;


use Apie\CommonValueObjects\Url;
use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\Contract\LicenseContract;
use Apie\ValueObjects\ValueObjectCompareInterface;
use Apie\ValueObjects\ValueObjectInterface;

class License implements ValueObjectInterface, LicenseContract
{
    use CompositeValueObjectWithExtension;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Url
     */
    private $url;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}