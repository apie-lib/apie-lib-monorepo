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
        if ($urlConfig && str_starts_with($urlConfig, 'postgres')) {
            return self::POSTGRES;
        }
        return match($configuration['connection_params']['driver']) {
            'pdo_sqlite' => self::SQLITE,
            'postgres' => self::POSTGRES,
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
                    'driver' => 'postgresql',
                    'server_version' => 16,
                    'url' => self::POSTGRES_CONNECTION_SETTING,
                ]
            ];
        }
        return [
            'connection_params' => [
                'url' => self::MYSQL_CONNECTION_SETTING,
            ],
        ];
    }
}