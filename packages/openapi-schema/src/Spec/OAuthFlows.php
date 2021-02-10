<?php


namespace Apie\OpenapiSchema\Spec;

use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\Exceptions\OAuthRequiresAuthorizationUrl;
use Apie\OpenapiSchema\Exceptions\OAuthRequiresTokenUrl;
use Apie\ValueObjects\ValueObjectInterface;

/**
 * @see https://swagger.io/specification/#oauth-flows-object
 */
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

    protected function validateProperties(): void
    {
        if ($this->implicit && null === $this->implicit->getAuthorizationUrl()) {
            throw new OAuthRequiresAuthorizationUrl();
        }
        if ($this->password && null === $this->password->getTokenUrl()) {
            throw new OAuthRequiresTokenUrl();
        }
        if ($this->clientCredentials && $this->clientCredentials->getTokenUrl()) {
            throw new OAuthRequiresTokenUrl();
        }
        if ($this->authorizationCode) {
            if (null === $this->authorizationCode->getAuthorizationUrl()) {
                throw new OAuthRequiresAuthorizationUrl();
            }
            if (null === $this->authorizationCode->getTokenUrl()) {
                throw new OAuthRequiresTokenUrl();
            }
        }
    }
}
