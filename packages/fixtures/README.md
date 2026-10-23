<img src="https://raw.githubusercontent.com/apie-lib/apie-lib-monorepo/main/docs/apie-logo.svg" width="100px" align="left" />
<h1>fixtures</h1>






 [![Latest Stable Version](https://poser.pugx.org/apie/fixtures/v)](https://packagist.org/packages/apie/fixtures) [![Total Downloads](https://poser.pugx.org/apie/fixtures/downloads)](https://packagist.org/packages/apie/fixtures) [![Latest Unstable Version](https://poser.pugx.org/apie/fixtures/v/unstable)](https://packagist.org/packages/apie/fixtures) [![License](https://poser.pugx.org/apie/fixtures/license)](https://packagist.org/packages/apie/fixtures) [![PHP Composer](https://apie-lib.github.io/projectCoverage/coverage-fixtures.svg)](https://apie-lib.github.io/projectCoverage/fixtures/index.html)  

[![PHP Composer](https://github.com/apie-lib/fixtures/actions/workflows/php.yml/badge.svg?event=push)](https://github.com/apie-lib/fixtures/actions/workflows/php.yml)

This package is part of the [Apie](https://github.com/apie-lib) library.
The code is maintained in a monorepo, so PR's need to be sent to the [monorepo](https://github.com/apie-lib/apie-lib-monorepo/pulls)

## Documentation
Reusable value objects, entities, enums, and application fixtures for Apie tests and
examples.

Install it only in development projects:
```bash
composer require --dev apie/fixtures
```

The package is not intended for production domain models. Import a fixture class in a
test when you need a ready-made Apie object, or use its factories as examples for your
own framework-free tests.
