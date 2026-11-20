<img src="https://raw.githubusercontent.com/apie-lib/apie-lib-monorepo/main/docs/apie-logo.svg" width="100px" align="left" />
<h1>common-value-objects</h1>






 [![Latest Stable Version](https://poser.pugx.org/apie/common-value-objects/v)](https://packagist.org/packages/apie/common-value-objects) [![Total Downloads](https://poser.pugx.org/apie/common-value-objects/downloads)](https://packagist.org/packages/apie/common-value-objects) [![Latest Unstable Version](https://poser.pugx.org/apie/common-value-objects/v/unstable)](https://packagist.org/packages/apie/common-value-objects) [![License](https://poser.pugx.org/apie/common-value-objects/license)](https://packagist.org/packages/apie/common-value-objects) [![PHP Composer](https://apie-lib.github.io/projectCoverage/coverage-common-value-objects.svg)](https://apie-lib.github.io/projectCoverage/common-value-objects/index.html)  

[![PHP Composer](https://github.com/apie-lib/common-value-objects/actions/workflows/php.yml/badge.svg?event=push)](https://github.com/apie-lib/common-value-objects/actions/workflows/php.yml)

This package is part of the [Apie](https://github.com/apie-lib) library.
The code is maintained in a monorepo, so PR's need to be sent to the [monorepo](https://github.com/apie-lib/apie-lib-monorepo/pulls)

## Documentation
A set of ready-to-use value objects and enums with Apie CMS/attributes already applied. They
can be used directly on your domain objects, or copied as examples for building your own.

### Standalone usage
Install it with:
```bash
composer require apie/common-value-objects
```

```php
<?php
use Apie\CommonValueObjects\Email;
use Apie\CommonValueObjects\FullName;
use Apie\CommonValueObjects\Gender;
use Apie\TextValueObjects\FirstName;
use Apie\TextValueObjects\LastName;

$name = new FullName(Gender::MALE, new FirstName('John'), new LastName('Doe'));
$email = new Email('john.doe@example.com');
```

| Class | Description |
| --- | --- |
| `Email` | A validated e-mail address (uses `egulias/email-validator`). |
| `EmailLocalPart` | The local part (before the `@`) of an e-mail address. |
| `Hostname` | A hostname such as a domain name or subdomain. |
| `FullName` | A composite value object of a `Gender`, `Apie\TextValueObjects\FirstName` and `LastName`. |
| `Gender` | Enum with `MALE`/`FEMALE` cases and a `getSalutation()` helper. |
| `SafeHtml` | HTML text sanitized with `symfony/html-sanitizer`, including custom sanitizers for embedded YouTube videos and `<span>` styling (see `Bridge/Symfony`). |
| `SemanticVersion` | A semantic version, optionally with a suffix, e.g. `1.0.0-dev`. |
| `ApplicationVersion` | A semantic version without a suffix, e.g. `1.0.0`. |
| `StarRating` | An integer rating on a scale of 0-5. |
| `Stars` | Enum rendering a `StarRating` as unicode stars, e.g. `★★★☆☆`. |
