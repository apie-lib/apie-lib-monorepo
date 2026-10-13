<img src="https://raw.githubusercontent.com/apie-lib/apie-lib-monorepo/main/docs/apie-logo.svg" width="100px" align="left" />
<h1>cms-layout-ionic</h1>






 [![Latest Stable Version](https://poser.pugx.org/apie/cms-layout-ionic/v)](https://packagist.org/packages/apie/cms-layout-ionic) [![Total Downloads](https://poser.pugx.org/apie/cms-layout-ionic/downloads)](https://packagist.org/packages/apie/cms-layout-ionic) [![Latest Unstable Version](https://poser.pugx.org/apie/cms-layout-ionic/v/unstable)](https://packagist.org/packages/apie/cms-layout-ionic) [![License](https://poser.pugx.org/apie/cms-layout-ionic/license)](https://packagist.org/packages/apie/cms-layout-ionic) [![PHP Composer](https://apie-lib.github.io/projectCoverage/coverage-cms-layout-ionic.svg)](https://apie-lib.github.io/projectCoverage/cms-layout-ionic/index.html)  

[![PHP Composer](https://github.com/apie-lib/cms-layout-ionic/actions/workflows/php.yml/badge.svg?event=push)](https://github.com/apie-lib/cms-layout-ionic/actions/workflows/php.yml)

This package is part of the [Apie](https://github.com/apie-lib) library.
The code is maintained in a monorepo, so PR's need to be sent to the [monorepo](https://github.com/apie-lib/apie-lib-monorepo/pulls)

## Documentation
An Apie CMS layout implementing the Ionic design system for `apie/cms`. It ships Twig templates/assets and a
factory that builds the `ComponentRendererInterface` implementation `apie/html-builders` needs to render pages.

### Standalone usage
```bash
composer require apie/cms-layout-ionic
```

Activate it by binding `Apie\HtmlBuilders\Interfaces\ComponentRendererInterface` to
`Apie\CmsLayoutIonic\IonicDesignSystemLayout::createRenderer()`, which builds a `TwigRenderer` from the package's
own templates and assets:

```php
use Apie\CmsLayoutIonic\IonicDesignSystemLayout;

$renderer = IonicDesignSystemLayout::createRenderer($uxIconRuntime, $assetManager);
```

In a Symfony application this is configured as a service factory:

```yaml
services:
    Apie\HtmlBuilders\Interfaces\ComponentRendererInterface:
        factory: ['Apie\CmsLayoutIonic\IonicDesignSystemLayout', 'createRenderer']
        arguments: ['@apie.ux_icon.twig_runtime', '@Apie\HtmlBuilders\Assets\AssetManager']
```

In Laravel, bind it the same way in a service provider's `boot()` method.
