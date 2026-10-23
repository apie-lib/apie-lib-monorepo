<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Resources;

use Apie\Core\Entities\EntityInterface;
use Apie\Core\Lists\StringSet;
use Apie\Core\ValueObjects\NonEmptyString;
use Apie\IanaValueObjects\CharacterSet\ActiveCharacterSet;
use Apie\IanaValueObjects\CharacterSet\CharacterSet;
use Apie\IanaValueObjects\HttpHeader\ActiveHttpHeader;
use Apie\IanaValueObjects\HttpHeader\HttpHeader;
use Apie\IanaValueObjects\HttpHeader\HttpHeaderStatus;
use Apie\IanaValueObjects\HttpHeader\StructuredType;
use Apie\IanaValueObjects\HttpStatus\ActiveHttpStatus;
use Apie\IanaValueObjects\HttpStatus\HttpStatus;
use Apie\IanaValueObjects\LanguageTag\ActiveLanguage;
use Apie\IanaValueObjects\LanguageTag\Language;
use Apie\IanaValueObjects\MediaType\ActiveMediaType;
use Apie\IanaValueObjects\MediaType\MediaType;
use Apie\IanaValueObjects\PortNumber\ActivePortNumber;
use Apie\IanaValueObjects\PortNumber\PortNumber;
use Apie\IanaValueObjects\TopLevelDomain\ActiveTopLevelDomain;
use Apie\IanaValueObjects\TopLevelDomain\TopLevelDomain;
use Apie\IanaValueObjects\UriScheme\ActiveUriScheme;
use Apie\IanaValueObjects\UriScheme\UriScheme;
use Apie\IntegrationTests\Apie\TypeDemo\Identifiers\IanaObjectIdentifier;

class IanaObject implements EntityInterface
{
    private IanaObjectIdentifier $id;

    public function __construct(
        public ?CharacterSet $set = null,
        public ?ActiveCharacterSet $activeSet = null,
        public ?HttpHeader $header = null,
        public ?ActiveHttpHeader $activeHeader = null,
        public ?HttpStatus $status = null,
        public ?ActiveHttpStatus $activeHttpStatus = null,
        public ?Language $language = null,
        public ?ActiveLanguage $activeLanguage = null,
        public ?MediaType $mediaType = null,
        public ?ActiveMediaType $activeMediaType = null,
        public ?PortNumber $portNumber = null,
        public ?ActivePortNumber $activePortNumber = null,
        public ?TopLevelDomain $topLevelDomain = null,
        public ?ActiveTopLevelDomain $activeTopLevelDomain = null,
        public ?UriScheme $scheme = null,
        public ?ActiveUriScheme $activeScheme = null,
    ) {
        $this->id = IanaObjectIdentifier::createRandom();
    }

    public function getId(): IanaObjectIdentifier
    {
        return $this->id;
    }

    public function getCharsetPreferredMimeName(): string
    {
        return $this->set?->getPreferredMimeName() ?? $this->activeSet?->getPreferredMimeName() ?? 'Missing';
    }

    public function getCharsetName(): string
    {
        return $this->set?->getName() ?? $this->activeSet?->getName() ?? 'Missing';
    }

    public function getCharsetMibEnum(): ?int
    {
        return $this->set?->getMibEnum() ?? $this->activeSet?->getMibEnum() ?? null;
    }

    public function getCharsetSource(): string
    {
        return $this->set?->getSource() ?? $this->activeSet?->getSource() ?? 'Missing';
    }

    public function getCharsetReference(): ?NonEmptyString
    {
        return $this->set?->getReference() ?? $this->activeSet?->getReference() ?? null;
    }

    public function getCharsetAliases(): StringSet
    {
        $list = [];
        foreach ($this->set?->getAliases() ?? [] as $option) {
            $list[] = $option;
        }
        foreach ($this->activeSet?->getAliases() ?? [] as $option) {
            $list[] = $option;
        }

        return new StringSet($list);
    }

    public function getCharsetNote(): ?NonEmptyString
    {
        return $this->set?->getNote() ?? $this->activeSet?->getNote() ?? null;
    }

    public function getHeaderFieldName(): string
    {
        return $this->header?->getFieldName() ?? $this->activeHeader?->getFieldName() ?? 'missing';
    }

    public function getHeaderStatus(): ?HttpHeaderStatus
    {
        return $this->header?->getStatus() ?? $this->activeHeader?->getStatus() ?? null;
    }

    public function getHeaderStructuredType(): ?StructuredType
    {
        return $this->header?->getStructuredType() ?? $this->activeHeader?->getStructuredType() ?? null;
    }

    public function getHeaderReference(): string
    {
        return $this->header?->getReference() ?? $this->activeHeader?->getReference() ?? 'Missing';
    }

    public function getHeaderComments(): ?NonEmptyString
    {
        return $this->header?->getComments() ?? $this->activeHeader?->getComments() ?? null;
    }

    public function getStatusValue(): string
    {
        return $this->status?->getValue() ?? $this->activeHttpStatus?->getValue() ?? 'Missing';
    }

    public function getStatusDescription(): string
    {
        return $this->status?->getDescription() ?? $this->activeHttpStatus?->getDescription() ?? 'Missing';
    }

    public function getStatusReference(): ?NonEmptyString
    {
        return $this->status?->getReference() ?? $this->activeHttpStatus?->getReference() ?? null;
    }
}
