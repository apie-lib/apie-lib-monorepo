<?php


namespace Apie\OpenapiSchema\Spec;


use Apie\CommonValueObjects\Url;
use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\Map\ScopesMap;
use Apie\ValueObjects\ValueObjectCompareInterface;
use Apie\ValueObjects\ValueObjectInterface;

class OAuthFlow implements ValueObjectInterface
{
    use CompositeValueObjectWithExtension;

    /**
     * @var Url|null
     */
    private $authorizationUrl;

    /**
     * @var Url|null
     */
    private $tokenUrl;

    /**
     * @var Url|null
     */
    private $refreshUrl;

    /**
     * @var ScopesMap|null
     */
    private $scopesMap;
}