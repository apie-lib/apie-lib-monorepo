<?php
namespace App\ApiePlayground\Example\Dtos;

use Apie\Core\Dto\DtoInterface;
use Apie\Core\ValueObjects\Utils;
use Apie\DoctrineEntityDatalayer\DoctrineEntityDatalayer;
use App\ApiePlayground\Example\Actions\PlaygroundConfiguration;
use App\ApiePlayground\Example\Enums\DatabaseConnection;
use App\ApiePlayground\Example\Enums\DatalayerImplementation;
use Symfony\Component\Yaml\Yaml;

final class ApieConfiguration implements DtoInterface
{
    public function __construct(
        public DatalayerImplementation $datalayerImplementation,
        public DatabaseConnection $usedDatabaseConnection,
    ) {
    }

    public static function createFromConfig(): self
    {
        $contents = Yaml::parseFile(PlaygroundConfiguration::CONFIG_FILE);
        return new ApieConfiguration(
            DatalayerImplementation::fromClass(
                $contents['apie']['datalayers']['default_datalayer'] ?? DoctrineEntityDatalayer::class
            ),
            DatabaseConnection::fromSetting(Utils::toArray($contents['apie']['doctrine'] ?? []))
        );
    }
}