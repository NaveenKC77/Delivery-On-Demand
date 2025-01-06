<?php

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'production' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'production_db',
            'user' => 'root',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8',
        ],
        'test' => [
            'adapter' => 'mysql',
            'host' => 'dev.****.us-east-1.rds.amazonaws.com',
            'name' => 'db_dod_test',
            'user' => 'admin',
            'pass' => '****',
            'port' => '3306',
            'charset' => 'utf8',
        ],
        'development' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'db_dod',
            'user' => 'root',
            'pass' => 'Root1234',
            'port' => '3306',
            'charset' => 'utf8',
        ],
    ],
    'version_order' => 'creation',
];
