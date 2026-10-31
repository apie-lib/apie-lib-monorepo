<img src="https://raw.githubusercontent.com/apie-lib/apie-lib-monorepo/main/docs/apie-logo.svg" width="100px" align="left" />
<h1>apie-common-plugin</h1>






 [![Latest Stable Version](https://poser.pugx.org/apie/apie-common-plugin/v)](https://packagist.org/packages/apie/apie-common-plugin) [![Total Downloads](https://poser.pugx.org/apie/apie-common-plugin/downloads)](https://packagist.org/packages/apie/apie-common-plugin) [![Latest Unstable Version](https://poser.pugx.org/apie/apie-common-plugin/v/unstable)](https://packagist.org/packages/apie/apie-common-plugin) [![License](https://poser.pugx.org/apie/apie-common-plugin/license)](https://packagist.org/packages/apie/apie-common-plugin) [![PHP Composer](https://apie-lib.github.io/projectCoverage/coverage-apie-common-plugin.svg)](https://apie-lib.github.io/projectCoverage/apie-common-plugin/index.html)  

[![PHP Composer](https://github.com/apie-lib/apie-common-plugin/actions/workflows/php.yml/badge.svg?event=push)](https://github.com/apie-lib/apie-common-plugin/actions/workflows/php.yml)

This package is part of the [Apie](https://github.com/apie-lib) library.
The code is maintained in a monorepo, so PR's need to be sent to the [monorepo](https://github.com/apie-lib/apie-lib-monorepo/pulls)

## Documentation
`apie/apie-common-plugin` is a Composer plugin (not a runtime library) that scans the `composer.json` of every
installed package for an `extra.apie-objects` list and generates `Apie\ApieCommonPlugin\AvailableApieObjectProvider`,
a static registry Apie uses to discover value objects, entities, lists, hashmaps and DTOs across installed
packages without an application maintaining a central list itself.

### Standalone usage
```bash
composer require apie/apie-common-plugin
```

Any package that wants its domain objects discovered adds them to its own `composer.json`:

```json
{
    "extra": {
        "apie-objects": [
            "Acme\\MyPackage\\ValueObject\\EmailAddress",
            "Acme\\MyPackage\\Entities\\Customer"
        ]
    }
}
```

Composer regenerates `AvailableApieObjectProvider` on every `composer install`/`composer update`. Non-interactive
environments must explicitly allow the plugin:

```json
{
    "config": {
        "allow-plugins": {
            "apie/apie-common-plugin": true
        }
    }
}
```

Read the discovered classes with `Apie\ApieCommonPlugin\ObjectProviderFactory::create()`, which returns an
`ObjectProvider` exposing `getAvailableValueObjects()`, `getAvailableLists()`, `getAvailableHashmaps()`,
`getAvailableDtos()` and `getAvailableServices()` (everything else, e.g. entities).

### Symfony integration
Loaded automatically via `apie/apie-bundle`. Its service file is empty on purpose: the plugin doesn't register
Symfony services itself, it only produces `AvailableApieObjectProvider`, which `apie/core` and `apie/common` read
to build the list of known domain objects per bounded context.

### Laravel integration
`apie/laravel-apie` registers the generated `Apie\ApieCommonPlugin\ApieCommonPluginServiceProvider`, which reads
`AvailableApieObjectProvider::getAvailableServices()` and binds each discovered class into the Laravel container so
it can be resolved like any other Laravel binding.
