<?php


namespace Apie\OpenapiSchema\Contract;


interface ContactContract
{
    public function getName(): ?string;

    public function getUrl(): ?string;

    public function getEmail(): ?string;
}