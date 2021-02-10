<?php
namespace Apie\OpenapiSchema\Contract;

use Apie\ValueObjects\ValueObjectInterface;

interface InfoContract extends ValueObjectInterface
{
    public function getTitle(): string;

    public function getDescription(): ?string;

    public function getTermsOfService(): ?string;

    public function getContact(): ?ContactContract;

    public function getLicense(): ?LicenseContract;

    public function getVersion(): string;
}
