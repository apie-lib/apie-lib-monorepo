<?php
namespace Apie\IanaValueObjects\PortNumber;

use Apie\Core\ValueObjects\Exceptions\InvalidStringForValueObjectException;
use Apie\Core\ValueObjects\IsStringValueObject;
use Apie\Core\ValueObjects\Utils;
use ReflectionClass;

trait IsPortNumber
{
    use IsStringValueObject;

    public static function validate(string $input): void
    {
        $input = Utils::toInt($input);
        if ($input < 0 || $input > 65535) {
            throw new InvalidStringForValueObjectException((string) $input, new ReflectionClass(static::class));
        }
    }

    public function getTransportInfo(?TransportType $transport): PortNumberAndTransport|ActivePortNumberAndTransport
    {
        $className = static::class . 'AndTransport';
        return new $className($this->internal . ($transport->value ?? ''));
    }
}
