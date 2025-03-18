<?php
namespace App\ApiePlayground\Example\Enums;

enum DatabaseConnection: string {
    public const MYSQL_CONNECTION_SETTING = "mysql://project:project@mysql:3306/project";
    public const POSTGRES_CONNECTION_SETTING = 'postgres://project:project@postgres:5432/project';

    case MYSQL = 'Mysql connection';
    case SQLITE = 'Sqlite connection';
    case POSTGRES = 'Postgres connection';

    public static function fromSetting(array $configuration): self
    {
        $urlConfig = $configuration['connection_params']['url'] ?? null;
        if ($urlConfig && str_starts_with($urlConfig, 'mysql')) {
            return self::MYSQL;
        }
        if ($urlConfig && str_starts_with($urlConfig, 'sqlite')) {
            return self::SQLITE;
        }
        if ($urlConfig && str_starts_with($urlConfig, 'pdo_pgsql')) {
            return self::POSTGRES;
        }
        return match($configuration['connection_params']['driver']) {
            'pdo_sqlite' => self::SQLITE,
            'pdo_pgsql' => self::POSTGRES,
            default => self::MYSQL,
        };
    }

    public function toDoctrineSetting(): array
    {
        if ($this === self::SQLITE) {
            return [
                'connection_params' => [
                    'driver' => 'pdo_sqlite',
                    'path' => "%kernel.project_dir%/var/db.sqlite",
                ]
            ];
        }
        if ($this === self::POSTGRES) {
            return [
                'connection_params' => [
                    'driver' => 'pdo_pgsql',
                    'dbname' => 'project',
                    'host' => 'postgres',
                    'port' => 5432,
                    'user' => 'project',   
                    'password' => 'project',
                    'server_version' => 16,
                ]
            ];
        }
        return [
            'connection_params' => [
                'driver' => 'pdo_mysql',
                'dbname' => 'project',
                'host' => 'mysql',
                'port' => 3306,
                'user' => 'project',
                'server_version' => '8',
                'password' => 'project',
            ],
        ];
    }
}