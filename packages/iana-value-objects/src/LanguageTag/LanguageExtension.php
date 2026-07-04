<?php
namespace Apie\IanaValueObjects\LanguageTag;

use Apie\Core\Lists\IdentifierList;
use Apie\Core\ValueObjects\SingleLetter;
use Apie\Core\ValueObjects\SnowflakeIdentifier;

final class LanguageExtension extends SnowflakeIdentifier
{
    public function __construct(
        private readonly SingleLetter $singleton,
        private readonly IdentifierList $identifiers
    ) {
    }

    public function getSingleton(): SingleLetter
    {
        return $this->singleton;
    }

    public function getIdentifiers(): IdentifierList
    {
        return $this->identifiers;
    }

    public static function getSeparator(): string
    {
        return '-';
    }
}