<?php


namespace Apie\ObjectAccessNormalizer\Interfaces;

interface LocalizationAwareInterface
{
    public function getAcceptLanguage(): ?string;
    public function getContentLanguage(): ?string;
}
