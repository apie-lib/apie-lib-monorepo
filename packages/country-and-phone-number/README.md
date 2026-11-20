<img src="https://raw.githubusercontent.com/apie-lib/apie-lib-monorepo/main/docs/apie-logo.svg" width="100px" align="left" />
<h1>country-and-phone-number</h1>






 [![Latest Stable Version](https://poser.pugx.org/apie/country-and-phone-number/v)](https://packagist.org/packages/apie/country-and-phone-number) [![Total Downloads](https://poser.pugx.org/apie/country-and-phone-number/downloads)](https://packagist.org/packages/apie/country-and-phone-number) [![Latest Unstable Version](https://poser.pugx.org/apie/country-and-phone-number/v/unstable)](https://packagist.org/packages/apie/country-and-phone-number) [![License](https://poser.pugx.org/apie/country-and-phone-number/license)](https://packagist.org/packages/apie/country-and-phone-number) [![PHP Composer](https://apie-lib.github.io/projectCoverage/coverage-country-and-phone-number.svg)](https://apie-lib.github.io/projectCoverage/country-and-phone-number/index.html)  

[![PHP Composer](https://github.com/apie-lib/country-and-phone-number/actions/workflows/php.yml/badge.svg?event=push)](https://github.com/apie-lib/country-and-phone-number/actions/workflows/php.yml)

This package is part of the [Apie](https://github.com/apie-lib) library.
The code is maintained in a monorepo, so PR's need to be sent to the [monorepo](https://github.com/apie-lib/apie-lib-monorepo/pulls)

## Documentation
Value objects that keep an international phone number and its country consistent.

### Standalone usage
Install it with:
```bash
composer require apie/country-and-phone-number
```

```php
use Apie\CountryAndPhoneNumber\CountryAndPhoneNumber;
use Apie\CountryAndPhoneNumber\Factories\PhoneNumberFactory;
use PrinsFrank\Standards\Country\CountryAlpha2;

$country = CountryAlpha2::US;
$value = new CountryAndPhoneNumber(
	$country,
	PhoneNumberFactory::createFrom('+12025550123', $country)
);
```

Construction validates that the phone number belongs to the selected country.
