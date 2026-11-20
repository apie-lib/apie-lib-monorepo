<?php
namespace Apie\IanaValueObjects\HttpStatus;

use Apie\Core\ValueObjects\NonEmptyString;

trait IsHttpStatus
{
    protected static function getData(): array
    {
        return require __DIR__ . '/../../fixtures/http-status-codes.php';
    }

    public function getValue(): string
    {
        return $this->getFieldValue('Value');
    }

    public function getDescription(): string
    {
        return $this->getFieldValue('Description');
    }

    public function getReference(): ?NonEmptyString
    {
        $reference = $this->getFieldValue('Reference');
        return $reference ? NonEmptyString::fromNative($reference) : null;
    }

    public function isActive(): bool
    {
        return (bool) $this->getFieldValue('Active');
    }
}
