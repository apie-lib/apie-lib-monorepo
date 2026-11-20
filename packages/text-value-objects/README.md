<img src="https://raw.githubusercontent.com/apie-lib/apie-lib-monorepo/main/docs/apie-logo.svg" width="100px" align="left" />
<h1>text-value-objects</h1>






 [![Latest Stable Version](https://poser.pugx.org/apie/text-value-objects/v)](https://packagist.org/packages/apie/text-value-objects) [![Total Downloads](https://poser.pugx.org/apie/text-value-objects/downloads)](https://packagist.org/packages/apie/text-value-objects) [![Latest Unstable Version](https://poser.pugx.org/apie/text-value-objects/v/unstable)](https://packagist.org/packages/apie/text-value-objects) [![License](https://poser.pugx.org/apie/text-value-objects/license)](https://packagist.org/packages/apie/text-value-objects) [![PHP Composer](https://apie-lib.github.io/projectCoverage/coverage-text-value-objects.svg)](https://apie-lib.github.io/projectCoverage/text-value-objects/index.html)  

[![PHP Composer](https://github.com/apie-lib/text-value-objects/actions/workflows/php.yml/badge.svg?event=push)](https://github.com/apie-lib/text-value-objects/actions/workflows/php.yml)

This package is part of the [Apie](https://github.com/apie-lib) library.
The code is maintained in a monorepo, so PR's need to be sent to the [monorepo](https://github.com/apie-lib/apie-lib-monorepo/pulls)

## Documentation
Common constrained text value objects: `FirstName`, `LastName`, `CompanyName` and
`SmallDatabaseText` (any trimmed text up to 255 characters, indexed for search), plus
`StrongPassword` and `EncryptedPassword` for password handling.

### Standalone usage
Install it with:
```bash
composer require apie/text-value-objects
```

```php
use Apie\TextValueObjects\FirstName;

$firstName = new FirstName('Ada');
echo $firstName->toString();
```

Each value object validates its constraints during construction (e.g. `StrongPassword` enforces
minimum length and character variety via a regular expression) and is usable without a framework.
