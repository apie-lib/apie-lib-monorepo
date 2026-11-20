<?php
namespace Apie\IanaValueObjects\PortNumber;

use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;
use Apie\IanaValueObjects\StaticDataValueObject;

class ActivePortNumberAndTransport implements StringValueObjectInterface
{
    use StaticDataValueObject;

    public function getDescription(): string
    {
        return $this->getFieldValue('Description');
    }

    public function getServiceName(): string
    {
        return $this->getFieldValue('Service Name');
    }
   
    public function isActive(): bool
    {
        return (bool) $this->getFieldValue('Active');
    }

    protected static function requiresActive(): bool
    {
        return true;
    }

    protected static function getData(): array
    {
        return require __DIR__ . '/../../fixtures/port-numbers.php';
    }
}
