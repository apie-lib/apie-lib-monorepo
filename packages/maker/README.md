<img src="https://raw.githubusercontent.com/apie-lib/apie-lib-monorepo/main/docs/apie-logo.svg" width="100px" align="left" />
<h1>maker</h1>






 [![Latest Stable Version](https://poser.pugx.org/apie/maker/v)](https://packagist.org/packages/apie/maker) [![Total Downloads](https://poser.pugx.org/apie/maker/downloads)](https://packagist.org/packages/apie/maker) [![Latest Unstable Version](https://poser.pugx.org/apie/maker/v/unstable)](https://packagist.org/packages/apie/maker) [![License](https://poser.pugx.org/apie/maker/license)](https://packagist.org/packages/apie/maker) [![PHP Composer](https://apie-lib.github.io/projectCoverage/coverage-maker.svg)](https://apie-lib.github.io/projectCoverage/maker/index.html)  

[![PHP Composer](https://github.com/apie-lib/maker/actions/workflows/php.yml/badge.svg?event=push)](https://github.com/apie-lib/maker/actions/workflows/php.yml)

This package is part of the [Apie](https://github.com/apie-lib) library.
The code is maintained in a monorepo, so PR's need to be sent to the [monorepo](https://github.com/apie-lib/apie-lib-monorepo/pulls)

## Documentation
Generates Apie domain object and bounded-context boilerplate (value objects, entities,
identifiers) either from a console command or programmatically.

### Standalone usage
```bash
composer require apie/maker
```
Use `Apie\Maker\CodeGenerators\CreateDomainObject` with a
`Apie\Maker\Dtos\DomainObjectDto` (built from `Apie\Maker\ValueObjects\PropertyDefinitionName`,
`Apie\Maker\Enums\PrimitiveType`, `IdType`, etc.) to generate a domain class straight to
disk via `Apie\Core\Other\FileWriterInterface`. The generator works without a framework;
`MakerServiceProvider` and the `apie:create:domain` console command are optional
integration layers on top of it.

### Symfony integration
`apie/apie-bundle` loads `packages/maker/maker.yaml`, which registers
`Apie\Maker\Command\ApieCreateDomainCommand` as a `console.command` and wires
`CreateDomainObject` with the `apie.bounded_contexts` and `apie.scan_bounded_contexts`
parameters from the bundle configuration, so the maker command becomes available as
`bin/console apie:create-domain-object`.

### Laravel integration
`apie/laravel-apie` registers the generated `Apie\Maker\MakerServiceProvider` (built
from `maker.yaml` by `apie/service-provider-generator`), which exposes the same
Artisan console command and services using the application's own bounded-context
configuration.
