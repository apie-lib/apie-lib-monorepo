<?php
namespace Apie\CountryAndPhonenumber\Exceptions;

use Apie\Core\Exceptions\ApieException;

class CountryAlreadyRegistered extends ApieException
{
    public function __construct(string $countryCode)
    {
        parent::__construct(sprintf('Country "%s" has already a registered class.', $countryCode));
    }
}
