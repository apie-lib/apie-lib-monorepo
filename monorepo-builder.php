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
            "doctrine/dbal" => "^4.4.4",
            "ext-gmp" => "*",
            "ext-bcmath" => "*",
        ],
        ComposerJsonSection::REQUIRE_DEV => [
            "ajgl/csv-rfc" => "0.4.0",
            "apie/service-provider-generator" => "0.14.0",
            "phpspec/prophecy-phpunit" => "^2.5",
            "phpstan/phpstan" => "2.2.8",
            "phpunit/phpcov" => "^10.0.1",
            'friendsofphp/php-cs-fixer' =>  "3.95.19",
            "symfony/doctrine-bridge" => "^7.2",
            "symfony/monolog-bundle" => "^3.10",
            'symfony/phpunit-bridge' =>  "^7.2",
            'symfony/finder' =>  "^7.2",
            'symplify/monorepo-builder' =>  '10.2.7',
        ],
    ]);
};