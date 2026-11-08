<img src="https://raw.githubusercontent.com/apie-lib/apie-lib-monorepo/main/docs/apie-logo.svg" width="100px" align="left" />
<h1>webdav</h1>






 [![Latest Stable Version](https://poser.pugx.org/apie/webdav/v)](https://packagist.org/packages/apie/webdav) [![Total Downloads](https://poser.pugx.org/apie/webdav/downloads)](https://packagist.org/packages/apie/webdav) [![Latest Unstable Version](https://poser.pugx.org/apie/webdav/v/unstable)](https://packagist.org/packages/apie/webdav) [![License](https://poser.pugx.org/apie/webdav/license)](https://packagist.org/packages/apie/webdav) [![PHP Composer](https://apie-lib.github.io/projectCoverage/coverage-webdav.svg)](https://apie-lib.github.io/projectCoverage/webdav/index.html)  

[![PHP Composer](https://github.com/apie-lib/webdav/actions/workflows/php.yml/badge.svg?event=push)](https://github.com/apie-lib/webdav/actions/workflows/php.yml)

This package is part of the [Apie](https://github.com/apie-lib) library.
The code is maintained in a monorepo, so PR's need to be sent to the [monorepo](https://github.com/apie-lib/apie-lib-monorepo/pulls)

## Documentation
Exposes Apie's file system (`apie/apie-file-system`) over WebDAV using [sabre/dav](https://sabre.io/dav/),
so Apie-stored files and resources can be browsed/edited with any WebDAV client.

### Standalone usage
Install it with:
```bash
composer require apie/webdav
```

Register `Apie\Webdav\RouteDefinitions\WebdavRouteDefinitionProvider` and
`Apie\Webdav\Controller\WebdavController` in your HTTP application, providing an
`Apie\ApieFileSystem\ApieFilesystemFactory` and a `ContextBuilderFactory`. The controller and
SabreDAV nodes can be mounted without Laravel or Symfony, although both have integration support.

### Symfony integration
Via `apie/apie-bundle`, `webdav.yaml` registers `WebdavController` (using the `kernel.debug`
parameter) as a controller service and `WebdavRouteDefinitionProvider` as a route definition, so
the WebDAV endpoint is available automatically.

### Laravel integration
Via `apie/laravel-apie`, the generated `Apie\Webdav\WebdavServiceProvider` registers the same
controller and route definition provider so the WebDAV endpoint is exposed the same way.
