<img src="https://raw.githubusercontent.com/apie-lib/apie-lib-monorepo/main/docs/apie-logo.svg" width="100px" align="left" />
<h1>meta-minimal</h1>






 [![Latest Stable Version](https://poser.pugx.org/apie/meta-minimal/v)](https://packagist.org/packages/apie/meta-minimal) [![Total Downloads](https://poser.pugx.org/apie/meta-minimal/downloads)](https://packagist.org/packages/apie/meta-minimal) [![Latest Unstable Version](https://poser.pugx.org/apie/meta-minimal/v/unstable)](https://packagist.org/packages/apie/meta-minimal) [![License](https://poser.pugx.org/apie/meta-minimal/license)](https://packagist.org/packages/apie/meta-minimal) [![PHP Composer](https://apie-lib.github.io/projectCoverage/coverage-meta-minimal.svg)](https://apie-lib.github.io/projectCoverage/meta-minimal/index.html)  

[![PHP Composer](https://github.com/apie-lib/meta-minimal/actions/workflows/php.yml/badge.svg?event=push)](https://github.com/apie-lib/meta-minimal/actions/workflows/php.yml)

This package is part of the [Apie](https://github.com/apie-lib) library.
The code is maintained in a monorepo, so PR's need to be sent to the [monorepo](https://github.com/apie-lib/apie-lib-monorepo/pulls)

## Documentation
Pure Composer meta-package with no code of its own. It requires the smallest set of
Apie packages needed for a working REST API:

```bash
composer require apie/meta-minimal
```

It requires: `apie/apie-common-plugin` (the shared Composer plugin used by Apie
packages), `apie/core` (domain-object primitives and attributes), and `apie/rest-api`
(the REST API layer built on top of the core).

It has no runtime API of its own — require the individual Apie packages you use in
application code and add a framework adapter (`apie/apie-bundle` or
`apie/laravel-apie`) plus a datalayer package to get a running REST API.
