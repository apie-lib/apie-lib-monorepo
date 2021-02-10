<?php


namespace Apie\OpenapiSchema\Contract;

use Apie\ValueObjects\ValueObjectInterface;

interface ContactContract extends ValueObjectInterface
{
    public function getName(): ?string;

    public function getUrl(): ?string;

    public function getEmail(): ?string;
}
