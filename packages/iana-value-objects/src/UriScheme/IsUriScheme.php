<?php
namespace Apie\IanaValueObjects\UriScheme;

use Apie\Core\ValueObjects\NonEmptyString;
use Apie\Core\ValueObjects\Utils;
use Apie\IanaValueObjects\StaticDataValueObject;

trait IsUriScheme
{
    use StaticDataValueObject;

    protected static function getData(): array
    {
        return require __DIR__ . '/../../fixtures/uri-schemes.php';
    }

    public function getTemplate(): ?NonEmptyString
    {
        $template = $this->getFieldValue('Template');
        return $template ? NonEmptyString::fromNative($template) : null;
    }

    public function getDescription(): ?NonEmptyString
    {
        $template = $this->getFieldValue('Description');
        return $template ? NonEmptyString::fromNative($template) : null;
    }

    public function getStatus()
    {
        return $this->getFieldValue('Status');
    }

    public function getCriSchemeNumber(): int
    {
        return Utils::toInt($this->getFieldValue('CRI Scheme Number'));
    }

    public function getWellKnownUriSupport(): ?NonEmptyString
    {
        $value = $this->getFieldValue('Well-Known URI Support');
        return (!$value || $value ==='-') ? null : NonEmptyString::fromNative($value);
    }

    public function getReference(): ?NonEmptyString
    {
        $template = $this->getFieldValue('Reference');
        return $template ? NonEmptyString::fromNative($template) : null;
    }

    public function getNotes(): ?NonEmptyString
    {
        $template = $this->getFieldValue('Notes');
        return $template ? NonEmptyString::fromNative($template) : null;
    }

    public function isActive(): bool
    {
        return (bool) $this->getFieldValue('Active');
    }
}
