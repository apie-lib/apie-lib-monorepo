<?php


namespace Apie\OpenapiSchema\Spec;

use Apie\CommonValueObjects\AuthenticationSchema;
use Apie\CommonValueObjects\Url;
use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\ValueObjects\ParameterIn;
use Apie\OpenapiSchema\ValueObjects\SecuritySchemeType;
use Apie\ValueObjects\ValueObjectCompareInterface;
use Apie\ValueObjects\ValueObjectInterface;

class SecurityScheme implements ValueObjectInterface, ValueObjectCompareInterface
{
    use CompositeValueObjectWithExtension;

    /**
     * @var SecuritySchemeType
     */
    private $type;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var ParameterIn|null
     */
    private $in;

    /**
     * @var AuthenticationSchema|null
     */
    private $scheme;

    /**
     * @var string|null
     */
    private $bearerFormat;

    /**
     * @var OAuthFlows|null
     */
    private $flows;

    /**
     * @var Url
     */
    private $openIdConnectUrl;

    private function __construct()
    {
    }
}