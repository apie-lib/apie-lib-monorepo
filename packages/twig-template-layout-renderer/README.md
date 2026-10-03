<img src="https://raw.githubusercontent.com/apie-lib/apie-lib-monorepo/main/docs/apie-logo.svg" width="100px" align="left" />
<h1>twig-template-layout-renderer</h1>






 [![Latest Stable Version](https://poser.pugx.org/apie/twig-template-layout-renderer/v)](https://packagist.org/packages/apie/twig-template-layout-renderer) [![Total Downloads](https://poser.pugx.org/apie/twig-template-layout-renderer/downloads)](https://packagist.org/packages/apie/twig-template-layout-renderer) [![Latest Unstable Version](https://poser.pugx.org/apie/twig-template-layout-renderer/v/unstable)](https://packagist.org/packages/apie/twig-template-layout-renderer) [![License](https://poser.pugx.org/apie/twig-template-layout-renderer/license)](https://packagist.org/packages/apie/twig-template-layout-renderer) [![PHP Composer](https://apie-lib.github.io/projectCoverage/coverage-twig-template-layout-renderer.svg)](https://apie-lib.github.io/projectCoverage/twig-template-layout-renderer/index.html)  

[![PHP Composer](https://github.com/apie-lib/twig-template-layout-renderer/actions/workflows/php.yml/badge.svg?event=push)](https://github.com/apie-lib/twig-template-layout-renderer/actions/workflows/php.yml)

This package is part of the [Apie](https://github.com/apie-lib) library.
The code is maintained in a monorepo, so PR's need to be sent to the [monorepo](https://github.com/apie-lib/apie-lib-monorepo/pulls)

## Documentation
Renders Apie layouts and components with Twig.

### Standalone usage
Install it with:
```bash
composer require apie/twig-template-layout-renderer
```

Create a `Twig\Environment`, register the Apie Twig extension, and use
`Apie\TwigTemplateLayoutRenderer\TwigRenderer` to render the selected layout. Custom layouts can
be scaffolded with `Apie\TwigTemplateLayoutRenderer\Skeleton\ClassCodeGenerator`. The
renderer can be used from a standalone Twig application; the console command and
service provider are optional conveniences.

### Symfony integration
Via `apie/apie-bundle`, `twig_template_layout_renderer.yaml` registers
`Apie\TwigTemplateLayoutRenderer\Command\CreateCustomLayoutRendererCommand` as a
`bin/console` command (backed by `ClassCodeGenerator`) to scaffold a new custom layout class.

### Laravel integration
Via `apie/laravel-apie`, the generated
`Apie\TwigTemplateLayoutRenderer\TwigTemplateLayoutRendererServiceProvider` registers the same
`ClassCodeGenerator` and exposes the layout-scaffolding command as an Artisan command.
