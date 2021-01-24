<?php


namespace Apie\OpenapiSchema\Spec;


use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\ValueObjects\ValueObjectCompareInterface;
use Apie\ValueObjects\ValueObjectInterface;

class OAuthFlows implements ValueObjectInterface
{
    use CompositeValueObjectWithExtension;

    /**
     * @var OAuthFlow|null
     */
    private $implicit;

    /**
     * @var OAuthFlow|null
     */
    private $password;

    /**
     * @var OAuthFlow|null
     */
    private $clientCredentials;

    /**
     * @var OAuthFlow|null
     */
    private $authorizationCode;

    private function __construct()
    {
    }
}