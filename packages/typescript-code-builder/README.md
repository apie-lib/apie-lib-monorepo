<img src="https://raw.githubusercontent.com/apie-lib/apie-lib-monorepo/main/docs/apie-logo.svg" width="100px" align="left" />
<h1>typescript-code-builder</h1>






 [![Latest Stable Version](https://poser.pugx.org/apie/typescript-code-builder/v)](https://packagist.org/packages/apie/typescript-code-builder) [![Total Downloads](https://poser.pugx.org/apie/typescript-code-builder/downloads)](https://packagist.org/packages/apie/typescript-code-builder) [![Latest Unstable Version](https://poser.pugx.org/apie/typescript-code-builder/v/unstable)](https://packagist.org/packages/apie/typescript-code-builder) [![License](https://poser.pugx.org/apie/typescript-code-builder/license)](https://packagist.org/packages/apie/typescript-code-builder) [![PHP Composer](https://apie-lib.github.io/projectCoverage/coverage-typescript-code-builder.svg)](https://apie-lib.github.io/projectCoverage/typescript-code-builder/index.html)  

[![PHP Composer](https://github.com/apie-lib/typescript-code-builder/actions/workflows/php.yml/badge.svg?event=push)](https://github.com/apie-lib/typescript-code-builder/actions/workflows/php.yml)

This package is part of the [Apie](https://github.com/apie-lib) library.
The code is maintained in a monorepo, so PR's need to be sent to the [monorepo](https://github.com/apie-lib/apie-lib-monorepo/pulls)

## Documentation
Low-level, framework-agnostic building blocks for generating TypeScript/JavaScript source code
safely (identifiers, declarations, expressions, control flow, files). It has no knowledge of
Apie APIs itself and is used by `apie/typescript-client-builder` to render the generated client.

### Standalone usage
Install it with:
```bash
composer require apie/typescript-code-builder
```

Use the DTOs in `Apie\TypescriptCodeBuilder\Dto` (such as `File`, `NamedFunction`,
`VariableAssignment`, `ImportStatement` and the expression classes) together with the enums in
`Apie\TypescriptCodeBuilder\Enums` (e.g. `TypescriptType`, `VariableDeclarationKind`) to compose
declarations and files, then render the resulting code with each object's `toTypescript()` or
`toJavascript()` method (both defined on `TypescriptFileExpressionInterface`). The package has no
framework dependency and is useful for any custom code generator.
