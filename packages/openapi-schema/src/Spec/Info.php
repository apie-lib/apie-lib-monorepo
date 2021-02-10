<?php


namespace Apie\OpenapiSchema\Spec;

use Apie\OpenapiSchema\Concerns\CompositeValueObjectWithExtension;
use Apie\OpenapiSchema\Contract\ContactContract;
use Apie\OpenapiSchema\Contract\InfoContract;
use Apie\OpenapiSchema\Contract\LicenseContract;

final class Info implements InfoContract
{
    use CompositeValueObjectWithExtension;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string|null
     */
    private $termsOfService;

    /**
     * @var ContactContract|Contact|null
     */
    private $contact;

    /**
     * @var LicenseContract|License|null
     */
    private $license;

    public function __construct(string $title, string $version)
    {
        $this->title = $title;
        $this->version = $version;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getTermsOfService(): ?string
    {
        return $this->termsOfService;
    }

    public function getContact(): ?ContactContract
    {
        return $this->contact;
    }

    public function getLicense(): ?LicenseContract
    {
        return $this->license;
    }

    public function getVersion(): string
    {
        return $this->version;
    }
}
