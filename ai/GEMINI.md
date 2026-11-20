# Package Development Instructions

All packages in Apie Lib follow a consistent structure.

## Package Structure

Each package in `packages/` should have:
- `src/`: Source code.
- `tests/`: PHPUnit tests.
- `composer.json`: Package-specific dependencies.
- `phpunit.xml`: Package-specific PHPUnit configuration.
- `README.md`: Documentation for the package.

## Development Workflow

1. **Adding a Package**: Use `bin/create-package <name>`.
2. **Testing**:
    - Run `bin/run-package-test <package-dir-name>` to run tests only for that package.
    - Run all tests locally with `php vendor/bin/phpunit`.
3. **Autoloading**: Autoloading is managed at the root `composer.json` but each package should have its own `composer.json` for standalone usage.

## Service Definitions & Framework Compatibility

To ensure compatibility with both Symfony and Laravel, follow these rules for service definitions:

- **Define Services in YAML**: Create a `<package-name>.yaml` file in the package root or `resources/config/`.
- **Use Symfony Syntax**: Use the standard Symfony service definition syntax (arguments, tags, aliases, etc.).
- **Avoid Framework-Specific Logic**: Keep service definitions as clean as possible. Use PSR interfaces for dependencies.
- **Service Discovery**: Utilize tags to allow other packages to discover your services. Common tags include:
    - `apie.core.context_builder`: For context builders.
    - `apie.datalayer`: For new data layers.
    - `console.command`: For CLI commands.

The `apie/service-provider-generator` will automatically handle the conversion of these definitions for Laravel, while Symfony will load them natively.

**Important Workflow for YAML Changes:**
After modifying a `.yaml` service definition, you MUST run:
- `bin/update-service-provider`: To regenerate Laravel-compatible service providers.
- `bin/fix-code-style`: To format the newly generated or modified files.
