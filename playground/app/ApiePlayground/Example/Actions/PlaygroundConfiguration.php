<?php
namespace App\ApiePlayground\Example\Actions;

use Apie\HtmlBuilders\Interfaces\ComponentRendererInterface;
use App\ApiePlayground\Example\Dtos\ApieConfiguration;
use Symfony\Component\Yaml\Yaml;

class PlaygroundConfiguration
{
    public const SF_CONFIG_FILE = '/var/www/html/config/packages/apie.yaml';

    public const LV_CONFIG_FILE = '/var/www/html/config/apie.php';

    public static function getConfigFile(): string
    {
        return file_exists(self::SF_CONFIG_FILE) ? self::SF_CONFIG_FILE : self::LV_CONFIG_FILE;
    }

    public static function readRawConfiguration(): array
    {
        $configFile = PlaygroundConfiguration::getConfigFile();
        if ($configFile === self::SF_CONFIG_FILE) {
            return Yaml::parseFile(self::SF_CONFIG_FILE);
        } else {
            return ['apie' => require(self::LV_CONFIG_FILE)];
        }

        return $contents;
    }

    public function applyConfiguration(
        ApieConfiguration $apieConfiguration
    ): ApieConfiguration {
        $contents = self::readRawConfiguration();
        $configFile = PlaygroundConfiguration::getConfigFile();
        if ($configFile === self::SF_CONFIG_FILE) {
            $doSave = function () use (&$contents) {
                file_put_contents(self::SF_CONFIG_FILE, Yaml::dump($contents));
            };
        } else {
            $doSave = function () use (&$contents) {
                file_put_contents(
                    self::LV_CONFIG_FILE,
                    '<?php' . PHP_EOL . 'return ' . var_export($contents['apie'], true) . ';'
                );
            };
        }
        
        $contents['apie']['datalayers']['default_datalayer'] = $apieConfiguration->datalayerImplementation->toClass()->name;
        $contents['apie']['doctrine'] = $apieConfiguration->usedDatabaseConnection->toDoctrineSetting();
        $contents['services'][ComponentRendererInterface::class] = $apieConfiguration->layout->toServiceDefinition();
        $doSave();
        return ApieConfiguration::createFromConfig();
    }

    public function resetConfiguration(): ApieConfiguration
    {
        $configFile = self::getConfigFile();
        file_put_contents(
            $configFile,
            file_get_contents('/var/www/html/' . basename($configFile))
        );
        return ApieConfiguration::createFromConfig();
    }
}