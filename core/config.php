<?php

class Config
{

    protected static $config;
    public static function get($name)
    {
        if (!isset(static::$config)) {
            static::initConfig();
        }

        return static::$config[$name];
    }

    protected static function initConfig()
    {
        $env = parse_ini_file(__DIR__ . '/../.env');
        static::$config = [
            'db' => [
                'driver' => $env['DB_DRIVER'] ?? 'pgsql',
                'host' => $env['DB_HOST'] ?? 'localhost',
                'db' => $env['DB'],
                'user' => $env['DB_USER'] ?? 'root',
                'password' => $env['DB_PASSWORD'],
                'port' => $env['DB_PORT'] ?? '5432'
            ]
        ];
    }
}
