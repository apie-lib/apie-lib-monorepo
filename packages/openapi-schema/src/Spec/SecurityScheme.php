<?php


namespace Apie\OpenapiSchema\Spec;

use Apie\CommonValueObjects\AuthenticationSchema;
use Apie\CommonValueObjects\Identifier;
use Apie\CommonValueObjects\Url;
use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\Exceptions\ApiKeySecuritySchemeRequiredFieldsMissing;
use Apie\OpenapiSchema\Exceptions\HttpSecuritySchemeRequiredFieldsMissing;
use Apie\OpenapiSchema\Exceptions\Oauth2SecuritySchemeRequiredFieldsMissing;
use Apie\OpenapiSchema\Exceptions\OpenIdSecuritySchemeRequiredFieldsMissing;
use Apie\OpenapiSchema\ValueObjects\ParameterIn;
use Apie\OpenapiSchema\ValueObjects\SecuritySchemeType;
use Apie\TypeJuggling\Exceptions\MissingValueException;
use Apie\ValueObjects\ValueObjectInterface;
use Throwable;

/**
 * @see https://swagger.io/specification/#security-scheme-object
 */
class SecurityScheme implements ValueObjectInterface
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
     * @var Identifier|null
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
     * @var Url|null
     */
    private $openIdConnectUrl;

    private function __construct()
    {
    }

    protected function validateProperties(): void
    {
        if (!$this->type) {
            return;
        }
        switch ($this->type->toNative()) {
            case SecuritySchemeType::API_KEY:
                $this->validateFields(new ApiKeySecuritySchemeRequiredFieldsMissing(), 'name', 'in');
                break;
            case SecuritySchemeType::HTTP:
                $this->validateFields(new HttpSecuritySchemeRequiredFieldsMissing(), 'scheme');
                if ($this->scheme->toNative() === AuthenticationSchema::BEARER && null === $this->bearerFormat) {
                    throw new MissingValueException('bearerFormat');
                }
                break;
            case SecuritySchemeType::OAUTH2:
                $this->validateFields(new Oauth2SecuritySchemeRequiredFieldsMissing(), 'flows');
                break;
            case SecuritySchemeType::OPEN_ID_CONNECT:
                $this->validateFields(new OpenIdSecuritySchemeRequiredFieldsMissing(), 'openIdConnectUrl');
        }
    }

    private function validateFields(Throwable $throwable, string... $fields)
    {
        foreach ($fields as $field) {
            if ($this->$field === null) {
                throw $throwable;
            }
        }
    }
}
