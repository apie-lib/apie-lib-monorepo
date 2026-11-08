<img src="https://raw.githubusercontent.com/apie-lib/apie-lib-monorepo/main/docs/apie-logo.svg" width="100px" align="left" />
<h1>ftp-server</h1>






 [![Latest Stable Version](https://poser.pugx.org/apie/ftp-server/v)](https://packagist.org/packages/apie/ftp-server) [![Total Downloads](https://poser.pugx.org/apie/ftp-server/downloads)](https://packagist.org/packages/apie/ftp-server) [![Latest Unstable Version](https://poser.pugx.org/apie/ftp-server/v/unstable)](https://packagist.org/packages/apie/ftp-server) [![License](https://poser.pugx.org/apie/ftp-server/license)](https://packagist.org/packages/apie/ftp-server) [![PHP Composer](https://apie-lib.github.io/projectCoverage/coverage-ftp-server.svg)](https://apie-lib.github.io/projectCoverage/ftp-server/index.html)  

[![PHP Composer](https://github.com/apie-lib/ftp-server/actions/workflows/php.yml/badge.svg?event=push)](https://github.com/apie-lib/ftp-server/actions/workflows/php.yml)

This package is part of the [Apie](https://github.com/apie-lib) library.
The code is maintained in a monorepo, so PR's need to be sent to the [monorepo](https://github.com/apie-lib/apie-lib-monorepo/pulls)

## Documentation
An FTP server adapter for Apie's virtual filesystem, built on ReactPHP Socket.

### Standalone usage
Install it with:
```bash
composer require apie/ftp-server
```

Use `Apie\FtpServer\FtpServerRunner::create()` from a long-running PHP process and provide an `Apie\ApieFileSystem\ApieFilesystemFactory` plus a `Apie\FtpServer\Factories\ServerFactoryInterface`. The package is independent of Laravel and Symfony, but it is intended for infrastructure (a standalone FTP daemon) rather than ordinary request handling.

### Symfony integration
Via `apie/apie-bundle`, `ftp.yaml` is loaded automatically and registers `Apie\FtpServer\FtpServerCommand` as a console command. The `apie.ftp_server.public_ip`, `apie.ftp_server.passive_min_port` and `apie.ftp_server.passive_max_port` configuration keys control the passive-mode port range and advertised IP.

### Laravel integration
Via `apie/laravel-apie`, the generated `Apie\FtpServer\FtpServerServiceProvider` is auto-registered and wires the same `FtpServerCommand` and server factories into the Laravel container.
