<?php


namespace Apie\OpenapiSchema\Spec;

use Apie\CommonValueObjects\Url;
use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\Map\ScopesMap;
use Apie\ValueObjects\ValueObjectInterface;

/**
 * @see https://swagger.io/specification/#oauth-flow-object
 */
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
     * @var ScopesMap
     */
    private $scopes;

    /**
     * @return Url|null
     */
    public function getAuthorizationUrl(): ?Url
    {
        return $this->authorizationUrl;
    }

    /**
     * @return Url|null
     */
    public function getTokenUrl(): ?Url
    {
        return $this->tokenUrl;
    }

    /**
     * @return Url|null
     */
    public function getRefreshUrl(): ?Url
    {
        return $this->refreshUrl;
    }

    /**
     * @return ScopesMap
     */
    public function getScopes(): ScopesMap
    {
        return $this->scopes;
    }
}
