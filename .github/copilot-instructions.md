# Apie Lib Monorepo

Apie is a suite of PHP composer packages implementing a **domain-objects-first** approach (as
opposed to database-first): everything is generated/automated (REST APIs, GraphQL, CMS, database
mapping, fakers) by reflecting on properly typed domain objects. PHP 8.4+, everything type-hinted.

## Repository layout

- `packages/<name>/` — ~50 independent composer packages (`src/`, `tests/`, own `composer.json`,
  `phpunit.xml`, `README.md`), autoloaded together via the root `composer.json` but each also
  installable standalone. Notable packages:
  - `core` — domain object primitives (Entities, ValueObjects, Identifiers, Attributes, BoundedContext).
  - `common`, `serializer`, `schema-generator`, `rest-api`, `graphql` — framework-agnostic core logic.
  - `apie-bundle` — Symfony adapter (`ApieExtension` loads YAML service definitions natively).
  - `laravel-apie` — Laravel adapter (`ApieServiceProvider` uses generated service providers).
  - `integration-tests` — cross-framework/datalayer matrix test suite.
  - `meta-minimal` / `meta-recommended` / `meta-maximum` — bundles of packages for different use cases.
- `playground/symfony-app` and `playground/laravel-app` — minimal apps for manual/integration testing.
- `bin/` — project tooling scripts (see below).
- `ai/` — subdirectory instructions for specific topics (read these before working in that area):
  - `ai/domain-objects.md` — how to write Value Objects, Entities, Enums, Identifiers, file uploads.
  - `ai/auditable-objects.md` — the `#[Auditable]` attribute and audit log mechanism.
  - `ai/integration-tests.md` — matrix testing system (`TestApplicationInterface`, `TestRequestInterface`, `MakeDataProviderMatrix`).
  - `ai/GEMINI.md` — package structure/service-definition conventions (duplicated below).

## Build / test / lint commands

- Install deps: `composer update` (or `composer install`).
- Run the full test suite with coverage: `bin/run-tests` (wraps `composer update && phpunit --stop-on-fail --coverage-php=... --coverage-text --stop-on-error`).
- Run tests for a single package: `bin/run-package-test <package-dir-name>` (e.g. `bin/run-package-test core`) — builds/runs a Docker container for that package.
- Run PHPUnit directly (fastest inner loop): `php vendor/bin/phpunit`
  - Single test file: `php vendor/bin/phpunit packages/core/tests/Path/To/SomeTest.php`
  - Single test method: `php vendor/bin/phpunit --filter '::test_method_name$' packages/core/tests/Path/To/SomeTest.php`
- Static analysis: `vendor/bin/phpstan analyse` (config: `phpstan.neon`, level 6; only a subset of packages are included in `paths:`).
- Code style: `bin/fix-code-style` (runs `php-cs-fixer` with `@PSR2,@PSR1,no_unused_imports,ordered_imports` across `bin/` and every package) — **you must run this after generating/modifying any code**, especially generated service providers.
- Scaffold a new package: `bin/create-package <name>` (creates `src/`, `tests/`, `composer.json`, `phpunit.xml`, `README.md`, an example class/test, then runs `composer update`).

## Architecture: multi-framework support (Symfony & Laravel)

- **Framework-agnostic core**: most logic lives in `core`, `common`, `serializer`, etc. These avoid
  framework-specific dependencies and depend only on PSR interfaces (PSR-3, PSR-11, PSR-14).
- **Unified service definitions**: services are declared once in Symfony-syntax YAML files
  (`<package-name>.yaml` in the package root or `resources/config/`), not duplicated per framework.
- **Symfony adapter** (`apie-bundle`): `ApieExtension` loads these YAML files directly into the
  Symfony container.
- **Laravel adapter** (`laravel-apie`): `ApieServiceProvider` registers generated PHP Service
  Providers, produced from the same YAML files by `apie/service-provider-generator`.
- **Service discovery**: a tag system (`TagMap` in Laravel, native tags in Symfony) lets the core
  discover plugins/context builders/datalayers regardless of framework. Common tags:
  `apie.core.context_builder`, `apie.datalayer`, `console.command`.

**If you edit any service-definition YAML file, you MUST run:**
1. `bin/update-service-provider` — regenerates the Laravel service providers from the YAML.
2. `bin/fix-code-style` — formats the regenerated/modified code.

## Key domain-object conventions (see `ai/domain-objects.md` for full examples)

- **Value Objects**: immutable; single-primitive ones use the `IsStringValueObject` trait +
  `StringValueObjectInterface` with a static `validate()`; composite ones use `CompositeValueObject`
  + `ValueObjectInterface` and should compose existing value objects rather than raw primitives
  ("primitive obsession").
- **Entities**: mutable, have an identifier, must never be constructible into an invalid state.
- **Identifiers**: dedicated value objects (e.g. extending `Apie\Core\Identifiers\Uuid`, implementing
  `IdentifierInterface`) used to reference entities — never reference entities directly.
- **Root Aggregates**: entities implementing `Apie\Core\Entities\RootAggregate` act as the API entry
  points for a Bounded Context.
- **File uploads**: use `Psr\Http\Message\UploadedFileInterface`; add `#[AllowMultipart]` to any
  entity that must accept multipart/form-data uploads.
- **Auditing**: add `#[Auditable]` to an entity to enable audit logging (creation/modification/removal
  by default; `readEvents`/`readAllEvents` to also audit reads; `permission` to restrict who can view
  the audit log). See `ai/auditable-objects.md`.
- **Collections**: use `Apie\Core\Lists\*` (e.g. `StringList`) and `Apie\Core\Lists\ItemSet` instead of
  plain arrays for typed lists/sets.

## Integration testing (see `ai/integration-tests.md` for full walkthrough)

- Integration tests run the *same* scenario across every supported application kernel (Symfony,
  Laravel) and datalayer (in-memory, Doctrine, Faker) as a PHPUnit data-provider matrix.
- Test classes use the `MakeDataProviderMatrix` trait; it discovers `create...Application` and
  `create...Request` factory methods on a helper (`IntegrationTestHelper`) and yields every
  combination as `(TestApplicationInterface, TestRequestInterface)` to the test method.
- New request scenarios are typically added as `create...Request` methods on
  `Apie\IntegrationTests\Concerns\CreatesApieBoundedContext`.
- Always call `$testApplication->bootApplication()` at the start and `->cleanApplication()` at the
  end of each test.
