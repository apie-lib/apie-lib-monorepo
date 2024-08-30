<?php

use Apie\ApieCommonPlugin\ApieCommonPlugin;
use Composer\IO\NullIO;

require __DIR__ . '/../vendor/autoload.php';
if (class_exists(ApieCommonPlugin::class)) {
    $factory = new Composer\Factory();
    $io = new NullIO();
    $composer = $factory->createComposer(
        $io,
        disablePlugins: true,
        cwd: __DIR__ . '/../',
        disableScripts: true
    );
    $plugin = new ApieCommonPlugin();
    $plugin->activate($composer, $io);
    $plugin->generatePhpCode();
}