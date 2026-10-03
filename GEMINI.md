# Apie Lib Monorepo - Project Instructions

This project is a PHP-based monorepo focused on Domain-Driven Design (DDD) principles. It aims to automate the creation of CMS, REST APIs, and database layers from domain objects.

## Architecture & Conventions

- **Domain-Driven Design (DDD)**: Follow the core principles of DDD. Avoid anemic domain models.
- **Entities**: Mutable objects with identifiers. They must never be in an invalid state.
- **Value Objects**: Immutable objects representing primitive values with business logic.
- **Root Aggregates**: Entities that serve as global entry points.
- **Identifiers**: Special value objects for referencing entities. No direct entity references.
- **Monorepo Structure**: Managed via `symplify/monorepo-builder`. Packages are located in `packages/`.

## Coding Standards

- **PHP Version**: PHP 8.4 or higher.
- **Type Hinting**: Everything should be type-hinted.
- **Style**: Adhere to PSR-1/PSR-2.
- **Code Fixer**: Use `bin/fix-code-style` or `vendor/bin/php-cs-fixer fix` to ensure consistency.

## Workflows

### Testing
- **Run all tests**: `bin/run-tests` (runs PHPUnit with coverage).
- **Run package tests**: `bin/run-package-test <package-name>`.
- **PHPUnit**: Configuration is in `phpunit.xml`.

### Static Analysis
- **PHPStan**: Configuration is in `phpstan.neon`. Run with `vendor/bin/phpstan`.

### Monorepo Management
- Packages are defined in `packages/`.
- Use `bin/create-package` to scaffold a new package.
- `monorepo-builder.php` contains the monorepo configuration.

## Multi-Framework Support (Symfony & Laravel)

Apie is designed to work seamlessly with both Symfony and Laravel. This is achieved through a multi-tier architecture:

- **Framework-Agnostic Core**: Most logic resides in packages like `core`, `common`, `serializer`, etc. These packages avoid direct dependencies on framework-specific code and use PSR interfaces (PSR-3, PSR-11, PSR-14) for external services.
- **Unified Service Definitions**: Services are defined in framework-agnostic YAML files (found in `packages/*/resources/config` or package roots). These files use a syntax similar to Symfony's service container.
- **Framework Adapters**:
  - **Symfony (`apie-bundle`)**: Uses `ApieExtension` to load the YAML service definitions directly into the Symfony Container.
  - **Laravel (`laravel-apie`)**: Uses `ApieServiceProvider`, which registers generated Service Providers. These providers are created from the same YAML files using `apie/service-provider-generator`.
- **Service Discovery**: A custom tagging system (leveraging `TagMap` in Laravel and native tags in Symfony) allows the core to discover plugins, context builders, and datalayers across both frameworks.

**Important Workflow for YAML Changes:**
If you modify any service definition YAML file, you MUST run the following commands to synchronize the frameworks and maintain code quality:
1. `bin/update-service-provider`: Regenerates the Laravel Service Providers from the YAML definitions.
2. `bin/fix-code-style`: Ensures the generated code adheres to project standards.

### Testing & Validation
The `playground/` directory contains minimal implementations for both frameworks:
- `playground/symfony-app`: A Symfony application for integration testing.
- `playground/laravel-app`: A Laravel application for integration testing.

Use these environments to ensure changes work correctly across both ecosystems.

## Subdirectory Instructions

- [ai/domain-objects.md](ai/domain-objects.md): How to create proper domain objects (Entities, Value Objects, Enums, File Uploads).
- [ai/auditable-objects.md](ai/auditable-objects.md): How to make domain objects auditable.
- [ai/integration-tests.md](ai/integration-tests.md): How the integration tests work (matrix testing for Symfony and Laravel).
- [ai/GEMINI.md](ai/GEMINI.md): Instructions for managing and developing packages.
