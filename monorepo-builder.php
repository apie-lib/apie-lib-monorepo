<?php
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\MonorepoBuilder\ValueObject\Option;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    // where are the packages located?
    $parameters->set(Option::PACKAGE_DIRECTORIES, [
        // default value
        __DIR__ . '/packages',
    ]);

    // "merge" command related

    // what extra parts to add after merge?
    $parameters->set(Option::DATA_TO_APPEND, [
        ComposerJsonSection::REQUIRE_DEV => [
            "apie/service-provider-generator" => "0.11.0",
            "phpspec/prophecy-phpunit" => "^2.0",
            "phpstan/phpstan" => "^1.8.2",
            'friendsofphp/php-cs-fixer' =>  "^3.8",
            'symfony/phpunit-bridge' =>  "6.*",
            'symfony/finder' =>  "6.*",
            'symplify/monorepo-builder' =>  '10.2.7',
        ],
    ]);
};