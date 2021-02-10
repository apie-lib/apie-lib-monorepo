<?php


namespace Apie\OpenapiSchema\Spec;

use Apie\CommonValueObjects\Email;
use Apie\CommonValueObjects\Url;
use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\Contract\ContactContract;
use Apie\ValueObjects\ValueObjectInterface;

/**
 * @see https://swagger.io/specification/#contact-object
 */
class Contact implements ValueObjectInterface, ContactContract
{
    use CompositeValueObjectWithExtension;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var Url|null
     */
    private $url;

    /**
     * @var Email|null
     */
    private $email;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }
}
