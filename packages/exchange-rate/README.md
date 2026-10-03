<img src="https://raw.githubusercontent.com/apie-lib/apie-lib-monorepo/main/docs/apie-logo.svg" width="100px" align="left" />
<h1>exchange-rate</h1>






 [![Latest Stable Version](https://poser.pugx.org/apie/exchange-rate/v)](https://packagist.org/packages/apie/exchange-rate) [![Total Downloads](https://poser.pugx.org/apie/exchange-rate/downloads)](https://packagist.org/packages/apie/exchange-rate) [![Latest Unstable Version](https://poser.pugx.org/apie/exchange-rate/v/unstable)](https://packagist.org/packages/apie/exchange-rate) [![License](https://poser.pugx.org/apie/exchange-rate/license)](https://packagist.org/packages/apie/exchange-rate) [![PHP Composer](https://apie-lib.github.io/projectCoverage/coverage-exchange-rate.svg)](https://apie-lib.github.io/projectCoverage/exchange-rate/index.html)  

[![PHP Composer](https://github.com/apie-lib/exchange-rate/actions/workflows/php.yml/badge.svg?event=push)](https://github.com/apie-lib/exchange-rate/actions/workflows/php.yml)

This package is part of the [Apie](https://github.com/apie-lib) library.
The code is maintained in a monorepo, so PR's need to be sent to the [monorepo](https://github.com/apie-lib/apie-lib-monorepo/pulls)

## Documentation
Looks up currency exchange rates for use in Apie domain objects (e.g. money value objects).

### Standalone usage
Install it with:
```bash
composer require apie/exchange-rate
```

Create an `ExchangeRateService` and pass ISO 4217 currency codes as strings:

```php
use Apie\ExchangeRate\ExchangeRateService;

$service = new ExchangeRateService();
$rate = $service->calculateConversionRate('USD', 'EUR');
```

Rates are retrieved from the public Frankfurter API.

### Symfony integration
Via `apie/apie-bundle`, `exchangerate.yaml` is loaded automatically and registers `Apie\ExchangeRate\ExchangeRateService`, using the Symfony `http_client` service if one is configured.

### Laravel integration
Via `apie/laravel-apie`, the generated `Apie\ExchangeRate\ExchangeRateServiceProvider` is auto-registered and binds `ExchangeRateService` into the Laravel container.
