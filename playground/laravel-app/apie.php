<?php

return [
        'cms' => [
           'dashboard_template' => 'apie/dashboard',
        ],
        'doctrine' => [
            // 'build_once' => true,
            'connection_params' => [
                'dbname'   => 'project',
                'host'     => 'mysql',
                'port'     => 3306,
                'user'     => 'project',
                'password' => 'project',
                'driver'   => 'pdo_mysql',
            ],
        ],
        'maker' => [
            'target_path' => base_path('generated/'),
            'target_namespace' => 'App\\Apie\\Maker',
        ],
        'datalayers' => [
            'default_datalayer' => 'Apie\\DoctrineEntityDatalayer\\DoctrineEntityDatalayer',
        ],
        'scan_bounded_contexts' => [
            'search_path' => base_path('generated/'),
            'search_namespace' => 'App\\Apie\\Maker',
        ],
        'remote_mcp_path' => '/mcp',
        'bounded_contexts' => [
            'make' => [
                'entities_folder' => '/packages/maker/src/BoundedContext/Resources',
                'entities_namespace' => 'Apie\\Maker\\BoundedContext\\Resources',
                'actions_folder' => '/packages/maker/src/BoundedContext/Actions',
                'actions_namespace' => 'Apie\\Maker\\BoundedContext\\Actions',
            ],
            'example' => [
                'entities_folder' => base_path('app/ApiePlayground/Example/Resources/'),
                'entities_namespace' => 'App\\ApiePlayground\\Example\\Resources',
                'actions_folder' => base_path('app/ApiePlayground/Example/Actions/'),
                'actions_namespace' => 'App\\ApiePlayground\\Example\\Actions',
            ],
            'permission' => [
                'entities_folder' => base_path('app/ApiePlayground/Permission/Resources/'),
                'entities_namespace' => 'App\\ApiePlayground\\Permission\\Resources',
                'actions_folder' => base_path('app/ApiePlayground/Permission/Actions/'),
                'actions_namespace' => 'App\\ApiePlayground\\Permission\\Actions',
            ],
            'types' => [
                'entities_folder' => base_path('app/ApiePlayground/Types/Resources/'),
                'entities_namespace' => 'App\\ApiePlayground\\Types\\Resources',
                'actions_folder' => base_path('app/ApiePlayground/Types/Actions/'),
                'actions_namespace' => 'App\\ApiePlayground\\Types\\Actions',
            ],
        ],
];