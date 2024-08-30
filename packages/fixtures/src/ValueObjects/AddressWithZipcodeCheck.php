<?php
namespace Apie\Fixtures\ValueObjects;

use Apie\Core\Attributes\Internal;
use Apie\Core\ValueObjects\CompositeValueObject;
use Apie\Core\ValueObjects\DatabaseText;
use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;

class AddressWithZipcodeCheck implements ValueObjectInterface
{
    use CompositeValueObject;

    private DatabaseText $street;

    private DatabaseText $streetNumber;

    private DatabaseText $zipcode;

    private DatabaseText $city;

    #[Internal]
    private bool $manual = true;

    public function __construct(
        DatabaseText $street,
        DatabaseText $streetNumber,
        DatabaseText $zipcode,
        DatabaseText $city
    ) {
        $this->street = $street;
        $this->streetNumber = $streetNumber;
        $this->zipcode = $zipcode;
        $this->city = $city;
    }

    public function getStreet(): DatabaseText
    {
        return $this->street;
    }

    public function getStreetNumber(): DatabaseText
    {
        return $this->streetNumber;
    }

    public function getZipcode(): DatabaseText
    {
        return $this->zipcode;
    }

    public function getCity(): DatabaseText
    {
        return $this->city;
    }

    public function isManualAddress(): bool
    {
        return $this->manual;
    }
}
