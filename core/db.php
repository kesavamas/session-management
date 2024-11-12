<?php
require_once(__DIR__ . '/config.php');
class DB
{
    protected static $instance;
    protected $conn;

    public function __construct()
    {
        $config = Config::get('db');
        $dsn = $config['driver'] . ':host=' . $config['host'] . ';port=' . $config['port'] . ';dbname=' . $config['db'];
        try {
            $this->conn = new PDO($dsn, $config['user'], $config['password']);
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new DB();
        }

        return static::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }
}
