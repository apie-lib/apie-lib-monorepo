<img src="https://raw.githubusercontent.com/apie-lib/apie-lib-monorepo/main/docs/apie-logo.svg" width="100px" align="left" />
<h1>meta-recommended</h1>






 [![Latest Stable Version](https://poser.pugx.org/apie/meta-recommended/v)](https://packagist.org/packages/apie/meta-recommended) [![Total Downloads](https://poser.pugx.org/apie/meta-recommended/downloads)](https://packagist.org/packages/apie/meta-recommended) [![Latest Unstable Version](https://poser.pugx.org/apie/meta-recommended/v/unstable)](https://packagist.org/packages/apie/meta-recommended) [![License](https://poser.pugx.org/apie/meta-recommended/license)](https://packagist.org/packages/apie/meta-recommended) [![PHP Composer](https://apie-lib.github.io/projectCoverage/coverage-meta-recommended.svg)](https://apie-lib.github.io/projectCoverage/meta-recommended/index.html)  

[![PHP Composer](https://github.com/apie-lib/meta-recommended/actions/workflows/php.yml/badge.svg?event=push)](https://github.com/apie-lib/meta-recommended/actions/workflows/php.yml)

This package is part of the [Apie](https://github.com/apie-lib) library.
The code is maintained in a monorepo, so PR's need to be sent to the [monorepo](https://github.com/apie-lib/apie-lib-monorepo/pulls)

## Documentation
Pure Composer meta-package with no code of its own. It requires `apie/meta-minimal`
plus the packages the Apie project recommends for a typical domain-driven application:

```bash
composer require apie/meta-recommended
```

It requires: `apie/core`, `apie/maker` (domain object code generation), `apie/meta-minimal`
(REST API foundations), `apie/common-value-objects`, `apie/country-and-phone-number`,
and `apie/date-value-objects` (ready-made value object libraries),
`apie/doctrine-entity-datalayer` (Doctrine ORM persistence), and `apie/faker`
(automatic fixture/demo data generation), plus `apie/text-value-objects`.

It has no runtime API of its own — use it as a Composer bundle and then use the APIs of
the individual installed packages. Prefer `apie/meta-minimal` if you don't need
Doctrine persistence, Faker fixtures, or the extra value-object packages.
