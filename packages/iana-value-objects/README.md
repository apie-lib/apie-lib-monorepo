<img src="https://raw.githubusercontent.com/apie-lib/apie-lib-monorepo/main/docs/apie-logo.svg" width="100px" align="left" />
<h1>iana-value-objects</h1>






 [![Latest Stable Version](https://poser.pugx.org/apie/iana-value-objects/v)](https://packagist.org/packages/apie/iana-value-objects) [![Total Downloads](https://poser.pugx.org/apie/iana-value-objects/downloads)](https://packagist.org/packages/apie/iana-value-objects) [![Latest Unstable Version](https://poser.pugx.org/apie/iana-value-objects/v/unstable)](https://packagist.org/packages/apie/iana-value-objects) [![License](https://poser.pugx.org/apie/iana-value-objects/license)](https://packagist.org/packages/apie/iana-value-objects) [![PHP Composer](https://apie-lib.github.io/projectCoverage/coverage-iana-value-objects.svg)](https://apie-lib.github.io/projectCoverage/iana-value-objects/index.html)  

[![PHP Composer](https://github.com/apie-lib/iana-value-objects/actions/workflows/php.yml/badge.svg?event=push)](https://github.com/apie-lib/iana-value-objects/actions/workflows/php.yml)

This package is part of the [Apie](https://github.com/apie-lib) library.
The code is maintained in a monorepo, so PR's need to be sent to the [monorepo](https://github.com/apie-lib/apie-lib-monorepo/pulls)

## Documentation
Value objects backed by IANA registries, including language tags, HTTP statuses, ports,
and transport types.

Install it with:
```bash
composer require apie/iana-value-objects
```

```php
use Apie\IanaValueObjects\LanguageAndRegion;
use Apie\IanaValueObjects\LanguageTag\Language;

$value = new LanguageAndRegion(Language::fromValue('en'));
echo $value->toPreferredValue();
```

The objects validate registry values during construction and have no framework
dependency.
