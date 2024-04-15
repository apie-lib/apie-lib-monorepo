<?php
namespace App\ApiePlayground\Example\Actions;

use App\ApiePlayground\Example\Dtos\ApieConfiguration;
use Symfony\Component\Yaml\Yaml;

class PlaygroundConfiguration
{
    public const CONFIG_FILE = '/var/www/html/config/packages/apie.yaml';

    public function applyConfiguration(
        ApieConfiguration $apieConfiguration
    ): ApieConfiguration {
        $contents = Yaml::parseFile(PlaygroundConfiguration::CONFIG_FILE);
        $contents['apie']['datalayers']['default_datalayer'] = $apieConfiguration->datalayerImplementation->toClass()->name;
        $contents['apie']['doctrine'] = $apieConfiguration->usedDatabaseConnection->toDoctrineSetting();
        file_put_contents(self::CONFIG_FILE, Yaml::dump($contents));
        return ApieConfiguration::createFromConfig();
    }

    public function resetConfiguration(): ApieConfiguration
    {
        file_put_contents(self::CONFIG_FILE, file_get_contents('/var/www/html/apie.yaml'));
        return ApieConfiguration::createFromConfig();
    }
}