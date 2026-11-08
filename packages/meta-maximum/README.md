<img src="https://raw.githubusercontent.com/apie-lib/apie-lib-monorepo/main/docs/apie-logo.svg" width="100px" align="left" />
<h1>meta-maximum</h1>






 [![Latest Stable Version](https://poser.pugx.org/apie/meta-maximum/v)](https://packagist.org/packages/apie/meta-maximum) [![Total Downloads](https://poser.pugx.org/apie/meta-maximum/downloads)](https://packagist.org/packages/apie/meta-maximum) [![Latest Unstable Version](https://poser.pugx.org/apie/meta-maximum/v/unstable)](https://packagist.org/packages/apie/meta-maximum) [![License](https://poser.pugx.org/apie/meta-maximum/license)](https://packagist.org/packages/apie/meta-maximum) [![PHP Composer](https://apie-lib.github.io/projectCoverage/coverage-meta-maximum.svg)](https://apie-lib.github.io/projectCoverage/meta-maximum/index.html)  

[![PHP Composer](https://github.com/apie-lib/meta-maximum/actions/workflows/php.yml/badge.svg?event=push)](https://github.com/apie-lib/meta-maximum/actions/workflows/php.yml)

This package is part of the [Apie](https://github.com/apie-lib) library.
The code is maintained in a monorepo, so PR's need to be sent to the [monorepo](https://github.com/apie-lib/apie-lib-monorepo/pulls)

## Documentation
Pure Composer meta-package with no code of its own. It bundles every optional Apie
integration for applications that want most of what Apie offers in one requirement:

```bash
composer require apie/meta-maximum
```

It requires: `apie/core`, `apie/meta-recommended` (which itself pulls in
`apie/meta-minimal` and the recommended value-object/datalayer/Faker packages),
`apie/cms-api-dropdown-option`, `apie/cms-layout-graphite`, `apie/console`,
`apie/graphql`, `apie/mcp-server`, `apie/regex-value-objects`, and
`apie/typescript-client-builder`.

It has no runtime API of its own — use it only as a Composer convenience bundle. Prefer
`apie/meta-minimal` or `apie/meta-recommended`, or requiring individual packages
directly, when you don't need CMS layouts, GraphQL, the MCP server, or the TypeScript
client generator.
