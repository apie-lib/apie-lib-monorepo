<img src="https://raw.githubusercontent.com/apie-lib/apie-lib-monorepo/main/docs/apie-logo.svg" width="100px" align="left" />
<h1>cms-layout-ugly</h1>






 [![Latest Stable Version](https://poser.pugx.org/apie/cms-layout-ugly/v)](https://packagist.org/packages/apie/cms-layout-ugly) [![Total Downloads](https://poser.pugx.org/apie/cms-layout-ugly/downloads)](https://packagist.org/packages/apie/cms-layout-ugly) [![Latest Unstable Version](https://poser.pugx.org/apie/cms-layout-ugly/v/unstable)](https://packagist.org/packages/apie/cms-layout-ugly) [![License](https://poser.pugx.org/apie/cms-layout-ugly/license)](https://packagist.org/packages/apie/cms-layout-ugly) [![PHP Composer](https://apie-lib.github.io/projectCoverage/coverage-cms-layout-ugly.svg)](https://apie-lib.github.io/projectCoverage/cms-layout-ugly/index.html)  

[![PHP Composer](https://github.com/apie-lib/cms-layout-ugly/actions/workflows/php.yml/badge.svg?event=push)](https://github.com/apie-lib/cms-layout-ugly/actions/workflows/php.yml)

This package is part of the [Apie](https://github.com/apie-lib) library.
The code is maintained in a monorepo, so PR's need to be sent to the [monorepo](https://github.com/apie-lib/apie-lib-monorepo/pulls)

## Documentation
An intentionally plain Apie CMS layout for applications that need a minimal UI.

### Standalone usage
Install it with:
```bash
composer require apie/cms-layout-ugly
```

Register `Apie\CmsLayoutUgly\UglyDesignSystemLayout` as the selected CMS layout. It is
a presentation package and can be used with a custom Twig application instead of a
full-stack framework.
