<img src="https://raw.githubusercontent.com/apie-lib/apie-lib-monorepo/main/docs/apie-logo.svg" width="100px" align="left" />
<h1>apie-file-system</h1>






 [![Latest Stable Version](https://poser.pugx.org/apie/apie-file-system/v)](https://packagist.org/packages/apie/apie-file-system) [![Total Downloads](https://poser.pugx.org/apie/apie-file-system/downloads)](https://packagist.org/packages/apie/apie-file-system) [![Latest Unstable Version](https://poser.pugx.org/apie/apie-file-system/v/unstable)](https://packagist.org/packages/apie/apie-file-system) [![License](https://poser.pugx.org/apie/apie-file-system/license)](https://packagist.org/packages/apie/apie-file-system) [![PHP Composer](https://apie-lib.github.io/projectCoverage/coverage-apie-file-system.svg)](https://apie-lib.github.io/projectCoverage/apie-file-system/index.html)  

[![PHP Composer](https://github.com/apie-lib/apie-file-system/actions/workflows/php.yml/badge.svg?event=push)](https://github.com/apie-lib/apie-file-system/actions/workflows/php.yml)

This package is part of the [Apie](https://github.com/apie-lib) library.
The code is maintained in a monorepo, so PR's need to be sent to the [monorepo](https://github.com/apie-lib/apie-lib-monorepo/pulls)

## Documentation
`apie/apie-file-system` exposes the resources of all bounded contexts as a virtual, read-only filesystem
(`Apie\ApieFileSystem\ApieFilesystem`, backed by `Virtual\RootFolder`/`BoundedContextFolder`), so any code that
understands a filesystem-like structure (an FTP server, WebDAV adapter, static export, ...) can browse Apie
resources without knowing about Apie itself.

### Standalone usage
```bash
composer require apie/apie-file-system
```

Build a filesystem with `Apie\ApieFileSystem\ApieFilesystemFactory`, which needs an `ActionDefinitionProvider` and
a `BoundedContextHashmap` (both provided by `apie/common`/`apie/core`):

```php
use Apie\ApieFileSystem\ApieFilesystemFactory;
use Apie\Core\Context\ApieContext;

$factory = new ApieFilesystemFactory($actionDefinitionProvider, $boundedContextHashmap);
$filesystem = $factory->create(new ApieContext());
```

The resulting `ApieFilesystem` exposes a `RootFolder` with one folder per bounded context, each containing the
resource lists and single-resource/export files defined in `Virtual/`.

### Symfony integration
Through `apie/apie-bundle`, `Apie\ApieFileSystem\ApieFilesystemFactory` is registered automatically with the
application's `ActionDefinitionProvider` and `BoundedContextHashmap`, ready to be injected wherever a virtual
filesystem view of your resources is needed.

### Laravel integration
`apie/laravel-apie` registers the generated `Apie\ApieFileSystem\ApieFileSystemServiceProvider`, binding
`ApieFilesystemFactory` as a singleton in the Laravel container in the same way.
