<?php

use Apie\ApieFileSystem\ApieFilesystem;
use Apie\ApieFileSystem\ApieFilesystemFactory;
use Apie\ApieFileSystem\Virtual\RootFolder;
use Apie\Common\ActionDefinitionProvider;
use Apie\Core\BoundedContext\BoundedContextHashmap;
use Apie\Core\ContextBuilders\ContextBuilderFactory;
use Apie\Fixtures\BoundedContextFactory;
use Apie\FtpServer\FtpServerCommand;
use Apie\FtpServer\FtpServerRunner;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require ('../vendor/autoload.php');
} else {
    require ('../../../vendor/autoload.php');
}

$contextBuilderFactory = new ContextBuilderFactory();
$hashmap = BoundedContextFactory::createHashmapWithMultipleContexts();
$actionDefinitionProvider = new ActionDefinitionProvider();
$factory = new ApieFilesystemFactory(
    $actionDefinitionProvider,
    $hashmap
);

$command = new FtpServerCommand(
    FtpServerRunner::create(),
    $factory,
    $contextBuilderFactory
);
$command->run(new ArrayInput([]), new ConsoleOutput());