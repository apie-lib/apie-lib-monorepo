<img src="https://raw.githubusercontent.com/apie-lib/apie-lib-monorepo/main/docs/apie-logo.svg" width="100px" align="left" />
<h1>cms-api-dropdown-option</h1>






 [![Latest Stable Version](https://poser.pugx.org/apie/cms-api-dropdown-option/v)](https://packagist.org/packages/apie/cms-api-dropdown-option) [![Total Downloads](https://poser.pugx.org/apie/cms-api-dropdown-option/downloads)](https://packagist.org/packages/apie/cms-api-dropdown-option) [![Latest Unstable Version](https://poser.pugx.org/apie/cms-api-dropdown-option/v/unstable)](https://packagist.org/packages/apie/cms-api-dropdown-option) [![License](https://poser.pugx.org/apie/cms-api-dropdown-option/license)](https://packagist.org/packages/apie/cms-api-dropdown-option) [![PHP Composer](https://apie-lib.github.io/projectCoverage/coverage-cms-api-dropdown-option.svg)](https://apie-lib.github.io/projectCoverage/cms-api-dropdown-option/index.html)  

[![PHP Composer](https://github.com/apie-lib/cms-api-dropdown-option/actions/workflows/php.yml/badge.svg?event=push)](https://github.com/apie-lib/cms-api-dropdown-option/actions/workflows/php.yml)

This package is part of the [Apie](https://github.com/apie-lib) library.
The code is maintained in a monorepo, so PR's need to be sent to the [monorepo](https://github.com/apie-lib/apie-lib-monorepo/pulls)

## Documentation
`apie/cms-api-dropdown-option` adds a REST endpoint that returns dropdown options for CMS forms in `apie/cms`, so
relation/identifier fields (and custom fields via your own provider) can be rendered as searchable dropdowns
instead of plain text inputs.

### Standalone usage
```bash
composer require apie/cms-api-dropdown-option
```

Implement `Apie\CmsApiDropdownOption\DropdownOptionProvider\DropdownOptionProviderInterface` (or extend the
`BaseDropdownOptionProvider` helper, which matches on the field of a resource property or action parameter) for a
custom option source, and tag it so it is picked up by `ChainedDropdownOptionProvider`, which tries every
registered provider until one supports the current field. `EntityIdentifierOptionProvider` already ships with the
package and provides options for entity-identifier fields.

### Symfony integration
Through `apie/apie-bundle`, `Apie\CmsApiDropdownOption\Controllers\DropdownOptionController` and its route
definitions (`DropdownOptionsForNewObjectRouteDefinition`, `DropdownOptionsForExistingObjectRouteDefinition`,
`DropdownOptionsForGlobalMethodRouteDefinition`) are registered automatically, and any service tagged with
`DropdownOptionProviderInterface` is collected into the chained provider.

### Laravel integration
`apie/laravel-apie` registers the generated `Apie\CmsApiDropdownOption\CmsDropdownServiceProvider`, wiring up the
same controller, route definitions and provider chaining for a Laravel application.
