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
        ComposerJsonSection::REQUIRE => [
            "doctrine/dbal" => ">=3.0",
        ],
        ComposerJsonSection::REQUIRE_DEV => [
            "apie/service-provider-generator" => "0.11.2",
            "phpspec/prophecy-phpunit" => "^2.0",
            "phpstan/phpstan" => "^1.11.2",
            "phpunit/phpcov" => "^8.2",
            'friendsofphp/php-cs-fixer' =>  "^3.8",
            "symfony/doctrine-bridge" => "^6.4",
            "symfony/monolog-bundle" => "3.*",
            'symfony/phpunit-bridge' =>  "^6.4",
            'symfony/finder' =>  "^6.4",
            'symplify/monorepo-builder' =>  '10.2.7',
        ],
    ]);
};