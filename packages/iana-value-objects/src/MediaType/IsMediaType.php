<?php
namespace Apie\IanaValueObjects\MediaType;

use Apie\IanaValueObjects\StaticDataValueObject;

trait IsMediaType
{
    use StaticDataValueObject;

    protected static function getData(): array
    {
        return require __DIR__ . '/../../fixtures/media-types.php';
    }

    public function getName(): string
    {
        return $this->getFieldValue('Name');
    }

    public function getTemplate(): string
    {
        return $this->getFieldValue('Template');
    }

    public function getReference(): string
    {
        return $this->getFieldValue('Reference');
    }

    public function isActive(): bool
    {
        return (bool) $this->getFieldValue('Active');
    }
}
